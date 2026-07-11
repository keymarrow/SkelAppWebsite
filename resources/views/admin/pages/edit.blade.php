@extends('admin.layout', ['title' => $config['label'].' – SkelApp CMS'])

@section('content')
  @php
    $previewBaseUrl = route('admin.pages.preview', ['slug' => $slug]);
    $previewUrl = route('admin.pages.preview', ['slug' => $slug, 'target' => $defaultPreviewTarget]);
  @endphp

  <div
    class="cms-page cms-page--editor"
    data-cms-editor
    data-preview-base-url="{{ $previewBaseUrl }}"
    data-preview-sync-url="{{ route('admin.pages.preview.sync', $slug) }}"
    data-preview-default-target="{{ $defaultPreviewTarget }}"
  >
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

    <div class="cms-editor-layout">
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
          <button
            type="submit"
            name="publish"
            value="1"
            formaction="{{ route('admin.pages.update', ['slug' => $slug, 'publish' => 1]) }}"
            class="cms-btn cms-btn-primary"
          >
            Save & publish
          </button>
        </footer>
      </form>

      <aside class="cms-preview-panel">
        <div class="cms-preview-panel-card">
          <div class="cms-preview-panel-header">
            <div class="cms-preview-panel-copy">
              <h2>Draft preview</h2>
              <p>See the public page while you edit. Text updates live. New image uploads appear after saving draft.</p>
            </div>

            <div class="cms-preview-toolbar">
              @if (count($previewTargets) > 1)
                <label class="cms-preview-target">
                  <span>Preview page</span>
                  <select class="cms-input" data-cms-preview-target>
                    @foreach ($previewTargets as $targetKey => $targetConfig)
                      <option value="{{ $targetKey }}" {{ $targetKey === $defaultPreviewTarget ? 'selected' : '' }}>
                        {{ $targetConfig['label'] }}
                      </option>
                    @endforeach
                  </select>
                </label>
              @endif

              <div class="cms-preview-actions">
                <button type="button" class="cms-btn cms-btn-ghost" data-cms-preview-refresh>Refresh</button>
                <a href="{{ $previewUrl }}" target="_blank" rel="noreferrer" class="cms-btn cms-btn-ghost" data-cms-preview-open>Open in tab</a>
              </div>
            </div>
          </div>

          <div class="cms-preview-meta">
            <div class="cms-preview-meta-info">
              <span class="cms-preview-badge">Admin-only preview</span>
              <span class="cms-preview-status" data-cms-preview-status>Preview ready.</span>
            </div>

            <div class="cms-preview-modes" role="group" aria-label="Preview viewport size">
              <button type="button" data-cms-preview-mode="desktop" class="is-active" aria-pressed="true" title="Desktop">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="3" y="4" width="18" height="13" rx="1.5"/>
                  <path d="M9 21h6"/>
                  <path d="M12 17v4"/>
                </svg>
                <span>Desktop</span>
              </button>
              <button type="button" data-cms-preview-mode="tablet" aria-pressed="false" title="Tablet">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="5" y="3" width="14" height="18" rx="2"/>
                  <path d="M11 18h2"/>
                </svg>
                <span>Tablet</span>
              </button>
              <button type="button" data-cms-preview-mode="mobile" aria-pressed="false" title="Mobile">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="7" y="2" width="10" height="20" rx="2"/>
                  <path d="M11 18h2"/>
                </svg>
                <span>Mobile</span>
              </button>
            </div>
          </div>

          <div class="cms-preview-frame-wrap" data-cms-preview-frame-wrap data-mode="desktop">
            <div class="cms-preview-frame-stage" data-cms-preview-frame-stage>
              <iframe
                src="{{ $previewUrl }}"
                title="{{ $config['label'] }} draft preview"
                class="cms-preview-frame"
                data-cms-preview-frame
              ></iframe>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  {{-- Shared media-library modal: opened by any image field on the page --}}
  <div
    class="cms-media-modal"
    data-cms-media-modal
    data-library-url="{{ route('admin.media.images.index') }}"
    data-upload-url="{{ route('admin.pages.images.upload') }}"
    hidden
  >
    <div class="cms-media-dialog" role="dialog" aria-modal="true" aria-labelledby="cms-media-title">
      <header class="cms-media-header">
        <div>
          <h3 id="cms-media-title">Image library</h3>
          <p>Pick a previously uploaded image, or upload a new one.</p>
        </div>
        <button type="button" class="cms-media-close" data-cms-media-close aria-label="Close">×</button>
      </header>

      <div class="cms-media-toolbar">
        <input type="search" placeholder="Search images…" data-cms-media-search class="cms-input">
        <label class="cms-btn cms-btn-ghost cms-media-upload-trigger">
          Upload new
          <input
            type="file"
            accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml"
            hidden
            data-cms-media-upload-input
          >
        </label>
      </div>

      <p class="cms-media-feedback" data-cms-media-feedback hidden></p>
      <p class="cms-media-empty" data-cms-media-empty hidden>No uploaded images yet — pick a file above to upload one.</p>
      <div class="cms-media-grid" data-cms-media-grid></div>

      <footer class="cms-media-actions">
        <button type="button" class="cms-btn cms-btn-ghost" data-cms-media-close>Cancel</button>
        <button type="button" class="cms-btn cms-btn-primary" data-cms-media-choose disabled>Choose selected</button>
      </footer>
    </div>
  </div>
@endsection
