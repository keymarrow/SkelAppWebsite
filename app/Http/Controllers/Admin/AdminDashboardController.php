<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $posts = NewsPost::query()
            ->latest('updated_at')
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'totalPosts' => NewsPost::query()->count(),
            'publishedPosts' => NewsPost::query()->where('is_published', true)->count(),
            'draftPosts' => NewsPost::query()->where('is_published', false)->count(),
            'recentPosts' => $posts,
        ]);
    }
}
