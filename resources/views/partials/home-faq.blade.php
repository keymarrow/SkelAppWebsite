@php
  $faqItems = content_list('faq.home_preview.items', []);
@endphp
<section class="faq-section" id="faq">
  <div class="faq-container">
    <div class="faq-header">
      <h2 {!! content_typography_html('faq.home_preview.heading') !!}>{!! content_text_html('faq.home_preview.heading', 'Frequently Asked Questions') !!}</h2>
      <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('faq.home_preview.read_more_label', 'Read more') }}</a>
    </div>
    <div class="faq-layout">
      <p {!! content_typography_html('faq.home_preview.subtitle', 'faq-subtitle') !!}>{!! content_text_html('faq.home_preview.subtitle', 'How it works') !!}</p>

      <div class="faq-list">
        @foreach ($faqItems as $idx => $item)
          @php
            $isOpen = $idx === 0;
            $qKey = "faq.home_preview.items.{$idx}.question";
            $aKey = "faq.home_preview.items.{$idx}.answer";
          @endphp
          <div class="faq-item @if($isOpen) active @endif">
            <button class="faq-question" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
              <span {!! content_typography_html($qKey) !!}>{!! content_text_html($qKey, $item['question'] ?? '') !!}</span>
              <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </button>
            <div class="faq-answer">
              <p {!! content_typography_html($aKey) !!}>{!! content_text_html($aKey, $item['answer'] ?? '') !!}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
