@php
  $title = 'SkelApp News';
  $bodyClass = 'news-index-page';
  $metaDescription = $metaDescription ?? 'Latest SkelApp news, product updates, retail guides, and company articles.';
@endphp

@extends('news.layout')

@php
  $query = request()->query();
  $baseState = [
    'sort' => $sortOrder !== 'latest' ? $sortOrder : null,
    'view' => $viewMode !== 'grid' ? $viewMode : null,
    'category' => $selectedCategory !== 'All' ? $selectedCategory : null,
    'year' => $selectedYear,
  ];
  $topicCategories = array_values(array_filter($categories, fn ($category) => $category !== 'All'));
@endphp

@section('content')
  <main class="news-shell">
    <header class="news-index-header">
      <div class="news-index-title-block">
        <h1>{{ $selectedCategory === 'All' ? 'All' : $selectedCategory }}</h1>
      </div>

      <div class="news-index-subnav">
        <div class="news-category-nav" aria-label="News categories">
          @foreach ($categories as $category)
            <a
              href="{{ route('news.index', array_merge($query, ['category' => $category === 'All' ? null : $category])) }}"
              class="{{ $selectedCategory === $category ? 'is-active' : '' }}"
            >
              {{ $category }}
            </a>
          @endforeach
        </div>

        <div class="news-toolbar-actions">
        <details class="news-menu">
          <summary>
            <span>Filter</span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 7h10M17 7h3M10 17H4M20 17h-7M14 4v6M10 14v6" />
            </svg>
          </summary>
          <div class="news-menu-panel news-filter-panel">
            <div class="news-filter-columns">
              <div class="news-filter-column">
                <h3>Topic</h3>
                @foreach ($topicCategories as $category)
                  <a
                    href="{{ route('news.index', array_merge($baseState, ['category' => $selectedCategory === $category ? null : $category])) }}"
                    class="{{ $selectedCategory === $category ? 'is-active' : '' }}"
                  >
                    <span class="news-choice-mark"></span>
                    <span>{{ $category }}</span>
                  </a>
                @endforeach
              </div>

              <div class="news-filter-column">
                <h3>Year</h3>
                @foreach ($years as $year)
                  <a
                    href="{{ route('news.index', array_merge($baseState, ['year' => $selectedYear === $year ? null : $year])) }}"
                    class="{{ $selectedYear === $year ? 'is-active' : '' }}"
                  >
                    <span class="news-choice-mark"></span>
                    <span>{{ $year }}</span>
                  </a>
                @endforeach
              </div>
            </div>

            <div class="news-menu-actions">
              <a href="{{ route('news.index', ['sort' => $sortOrder !== 'latest' ? $sortOrder : null, 'view' => $viewMode !== 'grid' ? $viewMode : null]) }}">Clear filters</a>
            </div>
          </div>
        </details>

        <details class="news-menu">
          <summary>
            <span>Sort</span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M7 10l5-5 5 5M7 14l5 5 5-5" />
            </svg>
          </summary>
          <div class="news-menu-panel news-sort-panel">
            <a href="{{ route('news.index', array_merge($baseState, ['sort' => 'latest'])) }}" class="{{ $sortOrder === 'latest' ? 'is-active' : '' }}">
              <span class="news-radio-mark"></span>
              <span>Newest → Oldest</span>
            </a>
            <a href="{{ route('news.index', array_merge($baseState, ['sort' => 'oldest'])) }}" class="{{ $sortOrder === 'oldest' ? 'is-active' : '' }}">
              <span class="news-radio-mark"></span>
              <span>Oldest → Newest</span>
            </a>
            <a href="{{ route('news.index', array_merge($baseState, ['sort' => 'title'])) }}" class="{{ $sortOrder === 'title' ? 'is-active' : '' }}">
              <span class="news-radio-mark"></span>
              <span>Title A-Z</span>
            </a>
          </div>
        </details>

        <div class="news-view-switch" aria-label="View options">
          <a
            href="{{ route('news.index', array_merge($query, ['view' => 'grid'])) }}"
            class="{{ $viewMode === 'grid' ? 'is-active' : '' }}"
            aria-label="Grid view"
          >
            <span></span><span></span><span></span><span></span>
          </a>
          <a
            href="{{ route('news.index', array_merge($query, ['view' => 'list'])) }}"
            class="{{ $viewMode === 'list' ? 'is-active' : '' }}"
            aria-label="List view"
          >
            <span></span><span></span><span></span>
          </a>
        </div>
      </div>
    </header>

    <section class="news-feed {{ $viewMode === 'list' ? 'is-list' : 'is-grid' }}">
      @forelse ($articles as $article)
        @if ($viewMode === 'list')
          <article class="news-list-row">
            <a href="{{ route('news.show', $article['slug']) }}" class="news-list-link">
              <div class="news-list-meta">
                <span class="news-list-category">{{ $article['categories'][0] }}</span>
                <span class="news-list-date">{{ \Illuminate\Support\Carbon::parse($article['date'])->format('M j, Y') }}</span>
              </div>

              <div class="news-list-copy">
                <h2>{{ $article['title'] }}</h2>
                <p>{{ $article['summary'] }}</p>
              </div>
            </a>
          </article>
        @else
          <article class="news-card">
            <a href="{{ route('news.show', $article['slug']) }}" class="news-card-link">
              <div
                class="news-card-art{{ !empty($article['featured_image_url']) ? ' has-image' : '' }}"
                style="--news-start: {{ $article['card_colors'][0] }}; --news-mid: {{ $article['card_colors'][1] }}; --news-end: {{ $article['card_colors'][2] }};"
              >
                @if (!empty($article['featured_image_url']))
                  <img
                    src="{{ $article['featured_image_url'] }}"
                    alt="{{ $article['title'] }}"
                    class="news-card-art-image"
                    loading="lazy"
                    decoding="async"
                  >
                  <div class="news-card-image-overlay"></div>
                @endif
                <span class="news-card-badge">{{ $article['card_label'] }}</span>
                @if (empty($article['featured_image_url']))
                  <div class="news-card-glow"></div>
                @endif
              </div>

              <div class="news-card-copy">
                <div class="news-card-meta">
                  <span>{{ \Illuminate\Support\Carbon::parse($article['date'])->format('F j, Y') }}</span>
                  <span>{{ $article['categories'][0] }}</span>
                </div>
                <h2>{{ $article['title'] }}</h2>
              </div>
            </a>
          </article>
        @endif
      @empty
        <div class="news-empty-state">
          <p>No articles match this filter yet.</p>
        </div>
      @endforelse
    </section>
  </main>
@endsection

@section('scripts')
  <script>
    (() => {
      const menus = document.querySelectorAll('.news-menu');
      if (!menus.length) return;

      document.addEventListener('click', (event) => {
        menus.forEach((menu) => {
          if (!menu.contains(event.target)) {
            menu.removeAttribute('open');
          }
        });
      });
    })();
  </script>
@endsection
