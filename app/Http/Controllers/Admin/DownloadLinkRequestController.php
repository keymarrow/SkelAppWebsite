<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadLinkRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class DownloadLinkRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        if (! DownloadLinkRequest::isAvailable()) {
            return view('admin.download-requests.index', [
                'requests' => new LengthAwarePaginator([], 0, 20, 1, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
                'search' => $search,
                'totalCount' => 0,
            ]);
        }

        $query = DownloadLinkRequest::query()->latest();

        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('phone', 'like', $like)
                  ->orWhere('destination', 'like', $like);
            });
        }

        $requests = $query->paginate(20)->withQueryString();

        return view('admin.download-requests.index', [
            'requests' => $requests,
            'search' => $search,
            'totalCount' => DownloadLinkRequest::totalCount(),
        ]);
    }

    public function destroy(DownloadLinkRequest $downloadRequest): RedirectResponse
    {
        $downloadRequest->delete();

        return redirect()
            ->route('admin.download-requests.index')
            ->with('status', 'Download link request deleted.');
    }
}
