<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');

        if (! ContactSubmission::isAvailable()) {
            return view('admin.contact-submissions.index', [
                'submissions' => new LengthAwarePaginator([], 0, 20, 1, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
                'search' => $search,
                'filter' => $filter,
                'totalCount' => 0,
                'unreadCount' => 0,
            ]);
        }

        $query = ContactSubmission::query()->latest();

        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('first_name', 'like', $like)
                  ->orWhere('last_name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('company', 'like', $like)
                  ->orWhere('phone', 'like', $like);
            });
        }

        if ($filter !== 'all') {
            if ($filter === 'unread') {
                $query->whereNull('read_at');
            } elseif ($filter === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $submissions = $query->paginate(20)->withQueryString();

        return view('admin.contact-submissions.index', [
            'submissions' => $submissions,
            'search' => $search,
            'filter' => $filter,
            'totalCount' => ContactSubmission::query()->count(),
            'unreadCount' => ContactSubmission::unreadCount(),
        ]);
    }

    public function show(ContactSubmission $submission): View
    {
        $submission->markRead();

        return view('admin.contact-submissions.show', [
            'submission' => $submission,
        ]);
    }

    public function destroy(ContactSubmission $submission): RedirectResponse
    {
        $submission->delete();

        return redirect()
            ->route('admin.submissions.index')
            ->with('status', 'Submission deleted.');
    }
}
