@extends('admin.layout', ['title' => $config['label'].' – SkelApp CMS'])

@section('content')
  <div class="cms-page">
    <header class="cms-page-header">
      <div>
        <h1>{{ $config['label'] }}</h1>
        <p class="cms-page-status">
          @if ($page->published_at)
            Last published {{ $page->published_at->diffForHumans() }}
            @if ($page->publishedBy) by {{ $page->publishedBy->name ?? 'admin' }} @endif
            @if ($page->hasUnpublishedChanges())
              · <strong class="cms-status-pill cms-status-pill--draft">Draft has unpublished changes</strong>
            @else
              · <strong class="cms-status-pill cms-status-pill--live">Live</strong>
            @endif
          @else
            Never published yet · <strong class="cms-status-pill cms-status-pill--draft">Draft only</strong>
          @endif
        </p>
      </div>

      <div class="cms-page-actions-header">
        @if ($page->hasUnpublishedChanges())
          <form method="POST" action="{{ route('admin.pages.revert', $slug) }}" class="cms-inline-form" onsubmit="return confirm('Discard draft changes and revert to the last published version?')">
            @csrf
            <button type="submit" class="cms-btn cms-btn-ghost">Revert draft</button>
          </form>
        @endif
      </div>
    </header>

    <form
      method="POST"
      action="{{ route('admin.pages.update', $slug) }}"
      enctype="multipart/form-data"
      class="cms-form"
      data-cms-form
    >
      @csrf

      @include($config['view'], ['content' => $content])

      <footer class="cms-form-actions">
        <button type="submit" name="publish" value="0" class="cms-btn cms-btn-ghost">Save draft</button>
        <button type="submit" name="publish" value="1" class="cms-btn cms-btn-primary">Save & publish</button>
      </footer>
    </form>
  </div>
@endsection
