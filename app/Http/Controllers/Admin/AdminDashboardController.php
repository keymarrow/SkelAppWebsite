<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\NewsPost;
use App\Models\VisitorEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    private const RANGES = [
        'today'  => 'Today',
        'week'   => 'This week',
        'month'  => 'This month',
        'year'   => 'This year',
        'custom' => 'Custom',
    ];

    public function __invoke(Request $request): View
    {
        $rangeKey = (string) $request->query('range', 'month');
        if (! array_key_exists($rangeKey, self::RANGES)) {
            $rangeKey = 'month';
        }

        [$rangeStart, $rangeEnd, $rangeLabel, $customStart, $customEnd] =
            $this->resolveRange($rangeKey, $request);

        $duration = max(60, $rangeStart->diffInSeconds($rangeEnd, true));
        $priorEnd = $rangeStart->copy()->subSecond();
        $priorStart = $priorEnd->copy()->subSeconds((int) $duration);

        $granularity = $this->granularity($rangeKey, $rangeStart, $rangeEnd);
        $buckets = $this->buildBuckets($rangeStart, $rangeEnd, $granularity);
        $chartLabels = array_column($buckets, 'label');
        $bucketCount = count($buckets);
        $zeroSeries = array_map(
            fn (array $b) => ['label' => $b['label'], 'value' => 0],
            $buckets
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
            'this_period' => array_fill(0, $bucketCount, 0),
            'prior_period' => array_fill(0, $bucketCount, 0),
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
                'new_visitors' => $this->bucketSeries($rangeStart, $rangeEnd, $granularity, 'new_visitors'),
                'returning_visitors' => $this->bucketSeries($rangeStart, $rangeEnd, $granularity, 'returning_visitors'),
                'organic_search' => $this->bucketSeries($rangeStart, $rangeEnd, $granularity, 'organic'),
                'most_engaged' => $this->bucketSeries($rangeStart, $rangeEnd, $granularity, 'engaged'),
            ];

            $visitorsChart = [
                'labels' => $chartLabels,
                'this_period' => array_column(
                    $this->bucketSeries($rangeStart, $rangeEnd, $granularity, 'all_sessions'),
                    'value'
                ),
                'prior_period' => $this->alignToLength(
                    array_column(
                        $this->bucketSeries($priorStart, $priorEnd, $granularity, 'all_sessions'),
                        'value'
                    ),
                    $bucketCount
                ),
            ];

            $usersInLast30 = $this->usersInLast30Minutes();

            $activePages = VisitorEvent::query()
                ->publicSite()
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
            'rangeLabel' => $rangeLabel,
            'ranges' => self::RANGES,
            'customStart' => $customStart,
            'customEnd' => $customEnd,
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

    /**
     * @return array{0:Carbon,1:Carbon,2:string,3:?string,4:?string}
     *         [rangeStart, rangeEnd, rangeLabel, customStart (Y-m-d), customEnd (Y-m-d)]
     */
    private function resolveRange(string $key, Request $request): array
    {
        $now = Carbon::now();
        $customStart = null;
        $customEnd = null;

        switch ($key) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy();
                $label = 'Today';
                break;

            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy();
                $label = 'This week';
                break;

            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy();
                $label = 'This year';
                break;

            case 'custom':
                [$start, $end, $customStart, $customEnd] = $this->parseCustomRange($request, $now);
                $label = $start->format('M j, Y').' – '.$end->format('M j, Y');
                break;

            case 'month':
            default:
                $start = $now->copy()->startOfMonth();
                $end = $now->copy();
                $label = 'This month';
                break;
        }

        return [$start, $end, $label, $customStart, $customEnd];
    }

    /**
     * @return array{0:Carbon,1:Carbon,2:string,3:string}
     */
    private function parseCustomRange(Request $request, Carbon $now): array
    {
        $rawStart = (string) $request->query('start', '');
        $rawEnd = (string) $request->query('end', '');

        try {
            $start = $rawStart !== '' ? Carbon::parse($rawStart)->startOfDay() : $now->copy()->subDays(6)->startOfDay();
        } catch (\Throwable) {
            $start = $now->copy()->subDays(6)->startOfDay();
        }
        try {
            $end = $rawEnd !== '' ? Carbon::parse($rawEnd)->endOfDay() : $now->copy()->endOfDay();
        } catch (\Throwable) {
            $end = $now->copy()->endOfDay();
        }

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }
        // Don't allow end in the future.
        if ($end->greaterThan($now)) {
            $end = $now->copy();
        }
        // Sanity ceiling: 5 years max to avoid runaway queries.
        if ($start->lessThan($now->copy()->subYears(5))) {
            $start = $now->copy()->subYears(5)->startOfDay();
        }

        return [$start, $end, $start->toDateString(), $end->toDateString()];
    }

    /**
     * Choose bucket granularity for a range. Returns one of: 'hour', 'day', 'month'.
     */
    private function granularity(string $key, Carbon $start, Carbon $end): string
    {
        if ($key === 'today') {
            return 'hour';
        }
        if ($key === 'year') {
            return 'month';
        }
        if ($key === 'week' || $key === 'month') {
            return 'day';
        }
        // Custom — pick from span.
        $hours = $start->diffInHours($end, true);
        $days = $start->diffInDays($end, true);
        if ($hours <= 36) {
            return 'hour';
        }
        if ($days <= 92) {
            return 'day';
        }
        return 'month';
    }

    /**
     * Build the empty bucket scaffold (keys are bucket identifiers, values are
     * ['label' => string, 'value' => int]).
     *
     * @return array<string, array{label:string,value:int}>
     */
    private function buildBuckets(Carbon $from, Carbon $to, string $gran): array
    {
        $buckets = [];
        $cursor = match ($gran) {
            'hour' => $from->copy()->startOfHour(),
            'day' => $from->copy()->startOfDay(),
            'month' => $from->copy()->startOfMonth(),
        };

        while ($cursor->lte($to)) {
            $buckets[$this->bucketKey($cursor, $gran)] = [
                'label' => $this->bucketLabel($cursor, $gran),
                'value' => 0,
            ];
            $cursor = match ($gran) {
                'hour' => $cursor->copy()->addHour(),
                'day' => $cursor->copy()->addDay(),
                'month' => $cursor->copy()->addMonth(),
            };
        }

        return $buckets;
    }

    private function bucketKey(Carbon $c, string $gran): string
    {
        return match ($gran) {
            'hour' => $c->format('Y-m-d H'),
            'day' => $c->toDateString(),
            'month' => $c->format('Y-m'),
        };
    }

    private function bucketLabel(Carbon $c, string $gran): string
    {
        return match ($gran) {
            'hour' => $c->format('g A'),
            'day' => $c->format('M j'),
            'month' => $c->format('M Y'),
        };
    }

    /**
     * SQL expression that produces the bucket key for a row's created_at. Driver-aware.
     */
    private function bucketSqlExpr(string $gran): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return match ($gran) {
                'hour' => "strftime('%Y-%m-%d %H', created_at)",
                'day' => "DATE(created_at)",
                'month' => "strftime('%Y-%m', created_at)",
            };
        }

        // MySQL / MariaDB
        return match ($gran) {
            'hour' => "DATE_FORMAT(created_at, '%Y-%m-%d %H')",
            'day' => "DATE(created_at)",
            'month' => "DATE_FORMAT(created_at, '%Y-%m')",
        };
    }

    /**
     * Build a series for one metric, returned as a list of ['label','value']
     * in chronological order.
     *
     * Metrics: 'new_visitors', 'returning_visitors', 'organic', 'engaged',
     *          'all_sessions'.
     *
     * @return list<array{label:string,value:int}>
     */
    private function bucketSeries(Carbon $from, Carbon $to, string $gran, string $metric): array
    {
        $buckets = $this->buildBuckets($from, $to, $gran);
        $bucketExpr = $this->bucketSqlExpr($gran);

        $base = VisitorEvent::query()->publicSite()->between($from, $to);

        $rows = match ($metric) {
            'new_visitors' => (clone $base)
                ->where('is_new_visitor', true)
                ->select(DB::raw("$bucketExpr as bk"), DB::raw('COUNT(*) as v'))
                ->groupBy('bk')->get(),

            'returning_visitors' => (clone $base)
                ->where('is_new_session', true)
                ->where('is_new_visitor', false)
                ->select(DB::raw("$bucketExpr as bk"), DB::raw('COUNT(*) as v'))
                ->groupBy('bk')->get(),

            'organic' => (clone $base)
                ->where('source', 'organic')
                ->select(DB::raw("$bucketExpr as bk"), DB::raw('COUNT(*) as v'))
                ->groupBy('bk')->get(),

            'all_sessions' => (clone $base)
                ->select(DB::raw("$bucketExpr as bk"), DB::raw('COUNT(DISTINCT session_id) as v'))
                ->groupBy('bk')->get(),

            'engaged' => (clone $base)
                ->select(
                    DB::raw("$bucketExpr as bk"),
                    DB::raw('session_id'),
                    DB::raw('COUNT(*) as ec')
                )
                ->groupBy('bk', 'session_id')
                ->havingRaw('COUNT(*) >= 3')
                ->get()
                ->groupBy('bk')
                ->map(fn ($g) => (object) ['bk' => (string) $g->first()->bk, 'v' => $g->count()])
                ->values(),

            default => collect(),
        };

        foreach ($rows as $row) {
            $key = (string) $row->bk;
            if (isset($buckets[$key])) {
                $buckets[$key]['value'] = (int) $row->v;
            }
        }

        return array_values($buckets);
    }

    /**
     * Pad or truncate $values so it has exactly $length entries.
     *
     * @param list<int> $values
     * @return list<int>
     */
    private function alignToLength(array $values, int $length): array
    {
        if (count($values) === $length) {
            return $values;
        }
        if (count($values) > $length) {
            return array_slice($values, 0, $length);
        }
        return array_pad($values, $length, 0);
    }

    /**
     * @return array{new_visitors:int,returning_visitors:int,organic_search:int,most_engaged:int}
     */
    private function kpiCounts(Carbon $from, Carbon $to): array
    {
        $base = fn (): Builder => VisitorEvent::query()->publicSite()->between($from, $to);

        $newVisitors = $base()->where('is_new_visitor', true)->count();

        $returningVisitors = $base()
            ->where('is_new_session', true)
            ->where('is_new_visitor', false)
            ->count();

        $organic = $base()->where('source', 'organic')->count();

        $engagedSessionsCount = $base()
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

    private function usersInLast30Minutes(): array
    {
        if (! VisitorEvent::isAvailable()) {
            return $this->emptyUsersInLast30Minutes();
        }

        $now = Carbon::now()->startOfMinute();
        $windowStart = $now->copy()->subMinutes(29);
        $events = VisitorEvent::query()
            ->publicSite()
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

        $distinctUsers = VisitorEvent::query()
            ->publicSite()
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
