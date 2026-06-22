<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class AffiliateApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');

        if (! AffiliateApplication::isAvailable()) {
            return view('admin.affiliate-applications.index', [
                'applications' => new LengthAwarePaginator([], 0, 20, 1, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
                'search' => $search,
                'filter' => $filter,
                'totalCount' => 0,
                'pendingCount' => 0,
            ]);
        }

        $query = AffiliateApplication::query()->latest();

        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('first_name', 'like', $like)
                  ->orWhere('last_name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('country', 'like', $like)
                  ->orWhere('phone_number', 'like', $like)
                  ->orWhere('primary_promotional_method', 'like', $like);
            });
        }

        if ($filter === 'pending') {
            $query->whereNull('reviewed_at');
        } elseif ($filter === 'reviewed') {
            $query->whereNotNull('reviewed_at');
        }

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.affiliate-applications.index', [
            'applications' => $applications,
            'search' => $search,
            'filter' => $filter,
            'totalCount' => AffiliateApplication::query()->count(),
            'pendingCount' => AffiliateApplication::pendingCount(),
        ]);
    }

    public function show(AffiliateApplication $application): View
    {
        $application->markReviewed();

        return view('admin.affiliate-applications.show', [
            'application' => $application,
        ]);
    }

    public function destroy(AffiliateApplication $application): RedirectResponse
    {
        $application->delete();

        return redirect()
            ->route('admin.affiliate-applications.index')
            ->with('status', 'Affiliate application deleted.');
    }
}
