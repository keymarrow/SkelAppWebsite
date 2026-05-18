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

    <div
      class="admin-live-preview-layout"
      data-news-preview-editor
      data-preview-base-url="{{ $previewUrl }}"
      data-preview-sync-url="{{ $previewSyncUrl }}"
    >
      <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" data-news-preview-form>
        @csrf
        @include('admin.news._form')
      </form>

      @include('admin.news._preview')
    </div>
  </main>
@endsection
