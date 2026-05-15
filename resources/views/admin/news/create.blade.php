@php($title = 'Create News Post')

@extends('admin.layout')

@section('content')
  <main class="admin-page">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Publishing</span>
        <h1>Create news post</h1>
      </div>
      <a href="{{ route('admin.posts.index') }}" class="admin-secondary-link">Back to posts</a>
    </header>

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
      @csrf
      @include('admin.news._form')
    </form>
  </main>
@endsection
