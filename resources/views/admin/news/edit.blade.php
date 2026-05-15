@php($title = 'Edit News Post')

@extends('admin.layout')

@section('content')
  <main class="admin-page">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Publishing</span>
        <h1>Edit news post</h1>
      </div>
      <a href="{{ route('admin.posts.index') }}" class="admin-secondary-link">Back to posts</a>
    </header>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}">
      @csrf
      @method('PUT')
      @include('admin.news._form')
    </form>

    <form id="delete-post-form" method="POST" action="{{ route('admin.posts.destroy', $post) }}">
      @csrf
      @method('DELETE')
    </form>
  </main>
@endsection
