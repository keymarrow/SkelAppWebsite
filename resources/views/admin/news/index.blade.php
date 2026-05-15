@php($title = 'Manage News Posts')

@extends('admin.layout')

@section('content')
  <main class="admin-page">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Publishing</span>
        <h1>News posts</h1>
      </div>
      <div class="admin-header-actions">
        <a href="{{ route('admin.posts.create') }}" class="admin-primary-button">New post</a>
      </div>
    </header>

    <section class="admin-panel">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Slug</th>
              <th>Status</th>
              <th>Published</th>
              <th>Updated</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($posts as $post)
              <tr>
                <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                <td><code>{{ $post->slug }}</code></td>
                <td>
                  <span class="admin-status {{ $post->is_published ? 'admin-status--published' : 'admin-status--draft' }}">
                    {{ $post->is_published ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td>{{ optional($post->published_at)->format('M j, Y H:i') ?? 'Not scheduled' }}</td>
                <td>{{ $post->updated_at?->format('M j, Y H:i') }}</td>
                <td class="admin-row-actions">
                  <a href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                  <a href="{{ route('news.show', $post->slug) }}" target="_blank" rel="noreferrer">View</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="admin-table-empty">No posts yet. Create your first one to get started.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $posts->links() }}
    </section>
  </main>
@endsection
