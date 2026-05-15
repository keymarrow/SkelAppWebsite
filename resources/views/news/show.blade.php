@php
  $title = ($article['meta_title'] ?? $article['title']) . ' | SkelApp News';
  $bodyClass = 'news-article-page';
  $metaDescription = $metaDescription ?? ($article['meta_description'] ?? $article['summary']);
@endphp

@extends('news.layout')

@section('content')
  <div class="news-article-rail">
    <span>{{ $article['title'] }}</span>
    <a href="{{ route('news.index') }}">All news</a>
  </div>

  <main class="news-shell">
    <article class="news-article">
      <header class="news-hero">
        <div class="news-hero-meta">
          <span>{{ \Illuminate\Support\Carbon::parse($article['date'])->format('F j, Y') }}</span>
          @foreach ($article['categories'] as $category)
            <span>{{ $category }}</span>
          @endforeach
        </div>

        <h1>{{ $article['title'] }}</h1>
        <p class="news-hero-summary">{{ $article['summary'] }}</p>
      </header>

      <div class="news-article-actions">
        <button
          type="button"
          class="news-audio-button news-audio-meta"
          aria-pressed="false"
          aria-label="Listen to article"
        >
          <span class="news-audio-play"></span>
          <span data-audio-label>Listen to article</span>
          <span>{{ $article['read_time'] }}</span>
        </button>

        <button type="button" class="news-share-button" data-share-title="{{ $article['title'] }}">
          Share
        </button>
      </div>

      <div class="news-article-body" id="article-body">
        @if (!empty($article['body_html']))
          {!! $article['body_html'] !!}
        @else
          @foreach ($article['sections'] as $section)
            <section class="news-copy-block">
              @if (!empty($section['heading']))
                <h2>{{ $section['heading'] }}</h2>
              @endif

              @foreach ($section['paragraphs'] as $paragraph)
                <p>{{ $paragraph }}</p>
              @endforeach
            </section>
          @endforeach
        @endif
      </div>
    </article>

    <aside class="news-related">
      <div class="news-related-heading">
        <h3>More news</h3>
        <a href="{{ route('news.index') }}">View all</a>
      </div>

      <div class="news-related-grid">
        @foreach ($related as $relatedArticle)
          <article class="news-related-card">
            <a href="{{ route('news.show', $relatedArticle['slug']) }}">
              <div
                class="news-card-art"
                style="--news-start: {{ $relatedArticle['card_colors'][0] }}; --news-mid: {{ $relatedArticle['card_colors'][1] }}; --news-end: {{ $relatedArticle['card_colors'][2] }};"
              >
                <span class="news-card-badge">{{ $relatedArticle['card_label'] }}</span>
                <div class="news-card-glow"></div>
              </div>
              <div class="news-card-copy">
                <div class="news-card-meta">
                  <span>{{ \Illuminate\Support\Carbon::parse($relatedArticle['date'])->format('F j, Y') }}</span>
                  <span>{{ $relatedArticle['categories'][0] }}</span>
                </div>
                <h4>{{ $relatedArticle['title'] }}</h4>
              </div>
            </a>
          </article>
        @endforeach
      </div>
    </aside>
  </main>
@endsection
