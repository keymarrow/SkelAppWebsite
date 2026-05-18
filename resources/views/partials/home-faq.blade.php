@php
  $faqItems = content_list('faq.home_preview.items', []);
@endphp
<section class="faq-section" id="faq">
  <div class="faq-container">
    <div class="faq-header">
      <h2>{{ content('faq.home_preview.heading', 'Frequently Asked Questions') }}</h2>
      <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('faq.home_preview.read_more_label', 'Read more') }}</a>
    </div>
    <div class="faq-layout">
      <p class="faq-subtitle">{{ content('faq.home_preview.subtitle', 'How it works') }}</p>

      <div class="faq-list">
        @foreach ($faqItems as $idx => $item)
          @php $isOpen = $idx === 0; @endphp
          <div class="faq-item @if($isOpen) active @endif">
            <button class="faq-question" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
              <span>{{ $item['question'] ?? '' }}</span>
              <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </button>
            <div class="faq-answer">
              <p>{{ $item['answer'] ?? '' }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
