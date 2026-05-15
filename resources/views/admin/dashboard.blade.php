@php($title = 'SkelApp CMS Dashboard')

@extends('admin.layout')

@section('content')
  <main class="admin-page">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Overview</span>
        <h1>News CMS</h1>
      </div>
      <a href="{{ route('admin.posts.create') }}" class="admin-primary-button">Create post</a>
    </header>

    <section class="admin-stats-grid">
      <article class="admin-stat-card">
        <span>Total posts</span>
        <strong>{{ $totalPosts }}</strong>
      </article>
      <article class="admin-stat-card">
        <span>Published</span>
        <strong>{{ $publishedPosts }}</strong>
      </article>
      <article class="admin-stat-card">
        <span>Drafts</span>
        <strong>{{ $draftPosts }}</strong>
      </article>
    </section>

    <section class="admin-panel">
      <div class="admin-panel-heading">
        <h2>Recent updates</h2>
        <a href="{{ route('admin.posts.index') }}">Manage all →</a>
      </div>

      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Published</th>
              <th>Updated</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentPosts as $post)
              <tr>
                <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                <td>
                  <span class="admin-status {{ $post->is_published ? 'admin-status--published' : 'admin-status--draft' }}">
                    {{ $post->is_published ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td>{{ optional($post->published_at)->format('M j, Y H:i') ?? 'Not scheduled' }}</td>
                <td>{{ $post->updated_at?->diffForHumans() }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="admin-table-empty">No posts yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
@endsection
