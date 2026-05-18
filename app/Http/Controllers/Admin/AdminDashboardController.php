<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\NewsPost;
use App\Models\VisitorEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    private const RANGES = [
        '7' => ['label' => 'Last 7 days', 'days' => 7],
        '30' => ['label' => 'Last 30 days', 'days' => 30],
        '90' => ['label' => 'Last 90 days', 'days' => 90],
        '365' => ['label' => 'Last 12 months', 'days' => 365],
    ];

    public function __invoke(Request $request): View
    {
        $rangeKey = $request->query('range', '30');
        if (! array_key_exists($rangeKey, self::RANGES)) {
            $rangeKey = '30';
        }
        $days = self::RANGES[$rangeKey]['days'];

        $now = Carbon::now();
        $rangeStart = $now->copy()->subDays($days - 1)->startOfDay();
        $rangeEnd = $now->copy()->endOfDay();
        $priorStart = $rangeStart->copy()->subDays($days);
        $priorEnd = $rangeStart->copy()->subSecond();
        $chartLabels = $this->dailyLabels($rangeStart, $rangeEnd);
        $zeroSeries = array_map(
            fn (string $label) => ['label' => $label, 'value' => 0],
            $chartLabels
        );

        $kpis = [
            'new_visitors' => $this->kpiCard('New visitors', 0, 0),
            'returning_visitors' => $this->kpiCard('Returning visitors', 0, 0),
            'organic_search' => $this->kpiCard('Organic search', 0, 0),
            'most_engaged' => $this->kpiCard('Most engaged', 0, 0),
        ];
        $sparklines = [
            'new_visitors' => $zeroSeries,
            'returning_visitors' => $zeroSeries,
            'organic_search' => $zeroSeries,
            'most_engaged' => $zeroSeries,
        ];
        $visitorsChart = [
            'labels' => $chartLabels,
            'this_period' => array_fill(0, count($chartLabels), 0),
            'prior_period' => array_fill(0, count($chartLabels), 0),
        ];
        $usersInLast30 = $this->emptyUsersInLast30Minutes();
        $activePages = [];

        if (VisitorEvent::isAvailable()) {
            $current = $this->kpiCounts($rangeStart, $rangeEnd);
            $prior = $this->kpiCounts($priorStart, $priorEnd);

            $kpis = [
                'new_visitors' => $this->kpiCard('New visitors', $current['new_visitors'], $prior['new_visitors']),
                'returning_visitors' => $this->kpiCard('Returning visitors', $current['returning_visitors'], $prior['returning_visitors']),
                'organic_search' => $this->kpiCard('Organic search', $current['organic_search'], $prior['organic_search']),
                'most_engaged' => $this->kpiCard('Most engaged', $current['most_engaged'], $prior['most_engaged']),
            ];

            $sparklines = [
                'new_visitors' => $this->dailySeries($rangeStart, $rangeEnd, 'is_new_visitor', true),
                'returning_visitors' => $this->dailySeries($rangeStart, $rangeEnd, 'is_returning_session'),
                'organic_search' => $this->dailySeries($rangeStart, $rangeEnd, 'source', 'organic'),
                'most_engaged' => $this->dailySeries($rangeStart, $rangeEnd, 'is_engaged'),
            ];

            $visitorsChart = [
                'labels' => $chartLabels,
                'this_period' => array_column($this->dailySeries($rangeStart, $rangeEnd, 'all_sessions'), 'value'),
                'prior_period' => array_column($this->dailySeries($priorStart, $priorEnd, 'all_sessions'), 'value'),
            ];

            $usersInLast30 = $this->usersInLast30Minutes();

            $activePages = VisitorEvent::query()
                ->between($rangeStart, $rangeEnd)
                ->select('path', DB::raw('COUNT(*) as hits'))
                ->groupBy('path')
                ->orderByDesc('hits')
                ->limit(8)
                ->get()
                ->map(fn ($row) => ['path' => $row->path, 'hits' => (int) $row->hits])
                ->all();
        }

        $submissionsAvailable = ContactSubmission::isAvailable();

        return view('admin.dashboard', [
            'rangeKey' => $rangeKey,
            'rangeLabel' => self::RANGES[$rangeKey]['label'],
            'ranges' => self::RANGES,
            'kpis' => $kpis,
            'sparklines' => $sparklines,
            'visitorsChart' => $visitorsChart,
            'usersInLast30' => $usersInLast30,
            'activePages' => $activePages,
            'totalSubmissions' => $submissionsAvailable ? ContactSubmission::query()->count() : 0,
            'unreadSubmissions' => $submissionsAvailable ? ContactSubmission::unreadCount() : 0,
            'recentSubmissions' => $submissionsAvailable ? ContactSubmission::query()->latest()->take(5)->get() : collect(),
            'totalPosts' => NewsPost::query()->count(),
            'publishedPosts' => NewsPost::query()->where('is_published', true)->count(),
        ]);
    }

    private function kpiCounts(Carbon $from, Carbon $to): array
    {
        $base = VisitorEvent::query()->between($from, $to);

        $newVisitors = (clone $base)->where('is_new_visitor', true)->count();

        // Returning visitors = sessions started in the range whose visitor existed before this range.
        $totalNewSessions = (clone $base)->where('is_new_session', true)->count();
        $returningVisitors = max(0, $totalNewSessions - $newVisitors);

        $organic = (clone $base)->where('source', 'organic')->count();

        // "Most engaged" = sessions with 3+ page views in the range.
        $engagedSessionsCount = (clone $base)
            ->select('session_id')
            ->groupBy('session_id')
            ->havingRaw('COUNT(*) >= 3')
            ->get()
            ->count();

        return [
            'new_visitors' => $newVisitors,
            'returning_visitors' => $returningVisitors,
            'organic_search' => $organic,
            'most_engaged' => $engagedSessionsCount,
        ];
    }

    private function kpiCard(string $label, int $current, int $prior): array
    {
        $delta = $prior === 0
            ? ($current === 0 ? 0.0 : 100.0)
            : (($current - $prior) / $prior) * 100;

        return [
            'label' => $label,
            'value' => $current,
            'delta_percent' => round($delta, 2),
            'delta_positive' => $delta >= 0,
            'prior' => $prior,
        ];
    }

    /**
     * Build a per-day series (label + value) for the given metric over [$from, $to].
     * $metric values:
     *   'all_sessions'              — count of distinct sessions per day
     *   'is_new_visitor' / 'is_new_session' (boolean) — count where flag is true
     *   'is_returning_session'      — new sessions minus new visitors
     *   'is_engaged'                — sessions with 3+ events that day
     *   'source' + $value           — count where source = $value
     */
    private function dailySeries(Carbon $from, Carbon $to, string $metric, mixed $value = true): array
    {
        $days = [];
        $cursor = $from->copy()->startOfDay();
        while ($cursor->lte($to)) {
            $days[$cursor->toDateString()] = ['label' => $cursor->format('M j'), 'value' => 0];
            $cursor->addDay();
        }

        if ($metric === 'all_sessions') {
            $rows = VisitorEvent::query()
                ->between($from, $to)
                ->select(DB::raw('DATE(created_at) as day, COUNT(DISTINCT session_id) as v'))
                ->groupBy('day')->get();
        } elseif ($metric === 'is_returning_session') {
            $rows = VisitorEvent::query()
                ->between($from, $to)
                ->where('is_new_session', true)
                ->where('is_new_visitor', false)
                ->select(DB::raw('DATE(created_at) as day, COUNT(*) as v'))
                ->groupBy('day')->get();
        } elseif ($metric === 'is_engaged') {
            // Sessions with >= 3 events per day.
            $rows = VisitorEvent::query()
                ->between($from, $to)
                ->select(DB::raw('DATE(created_at) as day, session_id, COUNT(*) as ec'))
                ->groupBy('day', 'session_id')
                ->havingRaw('ec >= 3')
                ->get()
                ->groupBy('day')
                ->map(fn ($grp) => (object) ['day' => $grp->first()->day, 'v' => $grp->count()])
                ->values();
        } elseif ($metric === 'source') {
            $rows = VisitorEvent::query()
                ->between($from, $to)
                ->where('source', (string) $value)
                ->select(DB::raw('DATE(created_at) as day, COUNT(*) as v'))
                ->groupBy('day')->get();
        } else {
            // Boolean column metric.
            $rows = VisitorEvent::query()
                ->between($from, $to)
                ->where($metric, $value)
                ->select(DB::raw('DATE(created_at) as day, COUNT(*) as v'))
                ->groupBy('day')->get();
        }

        foreach ($rows as $row) {
            $key = $row->day instanceof Carbon ? $row->day->toDateString() : (string) $row->day;
            if (isset($days[$key])) {
                $days[$key]['value'] = (int) $row->v;
            }
        }

        return array_values($days);
    }

    private function dailyLabels(Carbon $from, Carbon $to): array
    {
        $labels = [];
        $cursor = $from->copy()->startOfDay();
        while ($cursor->lte($to)) {
            $labels[] = $cursor->format('M j');
            $cursor->addDay();
        }
        return $labels;
    }

    private function usersInLast30Minutes(): array
    {
        if (! VisitorEvent::isAvailable()) {
            return $this->emptyUsersInLast30Minutes();
        }

        $now = Carbon::now()->startOfMinute();
        $windowStart = $now->copy()->subMinutes(29);
        $events = VisitorEvent::query()
            ->where('created_at', '>=', $windowStart)
            ->get(['session_id', 'created_at']);

        $perMinute = $events->groupBy(fn ($event) => $event->created_at->format('Y-m-d H:i'))
            ->map(fn ($group) => $group->pluck('session_id')->unique()->count())
            ->all();

        $series = [];
        $cursor = $windowStart->copy();
        for ($i = 0; $i < 30; $i++) {
            $key = $cursor->format('Y-m-d H:i');
            $count = (int) ($perMinute[$key] ?? 0);
            $series[] = ['label' => $cursor->format('H:i'), 'value' => $count];
            $cursor->addMinute();
        }

        // Total unique sessions in the 30-min window
        $distinctUsers = VisitorEvent::query()
            ->where('created_at', '>=', $windowStart)
            ->distinct()
            ->count('session_id');

        return [
            'total' => $distinctUsers,
            'series' => $series,
        ];
    }

    private function emptyUsersInLast30Minutes(): array
    {
        $now = Carbon::now()->startOfMinute();
        $windowStart = $now->copy()->subMinutes(29);
        $series = [];
        $cursor = $windowStart->copy();

        for ($i = 0; $i < 30; $i++) {
            $series[] = ['label' => $cursor->format('H:i'), 'value' => 0];
            $cursor->addMinute();
        }

        return [
            'total' => 0,
            'series' => $series,
        ];
    }
}
