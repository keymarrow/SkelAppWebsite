<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SkelApp – The Best POS in Tanzania.</title>
<link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
<link rel="preload" as="image" href="{{ asset('assets/HeroImage.webp') }}" media="(min-width: 901px)" fetchpriority="high" />
<link rel="preload" as="image" href="{{ asset('assets/HeroImage.jpg') }}" media="(max-width: 900px)" fetchpriority="high" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body>
@include('partials.site-nav', ['isHome' => true])
<section class="hero" id="overview">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
<div class="hero-content">
  <div class="hero-left">
    <h1>For Businesses That Think Bigger.</h1>
    <p>Mobile-ready POS which gives you control to your business.</p>
    <a href="#" class="btn-download">Download Now</a>
  </div>

  <div class="hero-right">
    <div class="testimonial-card">
      <div class="stars">
        <img src="{{ asset('assets/Stars.svg') }}" alt="5 star rating">
      </div>
      <blockquote>
        SkelApp is game changer to everyone who really care about gain control of their business. It helps me stay on top of my business 24/7 365.
      </blockquote>
      <cite>Nuh – TechSoko Shop, TZ</cite>
    </div>
  </div>
</div>
</section>

<section class="app-showcase" id="showcase">
  <div class="showcase-container">
    <div class="showcase-card">
      <div class="showcase-header">
        <h2 class="showcase-title">POS, But 1% Better</h2>
        <p class="showcase-subtitle showcase-subtitle-primary">
          SkelApp helps you manage sales, inventory, purchases, and expenses in one powerful POS platform
        </p>
        <p class="showcase-subtitle showcase-subtitle-secondary">
          Built for retail businesses of any size.
        </p>
        <p class="showcase-subtitle showcase-subtitle-mobile">
          SkelApp helps you manage sales, inventory, purchases, and expenses for retail businesses of any size.
        </p>

        <div class="app-buttons">
          <a href="#" class="store-badge store-badge-apple" aria-label="Download on the App Store">
            <img src="{{ asset('assets/applebadge.png') }}" alt="Download on the App Store">
          </a>

          <a href="#" class="store-badge store-badge-google" aria-label="Get it on Google Play">
            <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play">
          </a>
        </div>
      </div>

      <div class="device-mockup">
        <img src="{{ asset('assets/devicemockup.webp') }}" alt="SkelApp on desktop" class="device-mockup-image desktop-only-img" loading="lazy" decoding="async">
        <img src="{{ asset('assets/Mobilehomeview.png') }}" alt="SkelApp on mobile" class="device-mockup-image mobile-only-img" loading="lazy" decoding="async">
      </div>
    </div>

    <div class="showcase-points desktop-only" aria-label="SkelApp highlights">
      <article class="showcase-point">
        <div class="showcase-point-heading">
          <img src="{{ asset('assets/speed.svg') }}" alt="" class="showcase-point-icon" aria-hidden="true">
          <h3>Built for Speed</h3>
        </div>
        <p>Complete sales in seconds</p>
      </article>

      <article class="showcase-point">
        <div class="showcase-point-heading">
          <img src="{{ asset('assets/retail.svg') }}" alt="" class="showcase-point-icon" aria-hidden="true">
          <h3>Designed for Retail</h3>
        </div>
        <p>Made for daily shop operations</p>
      </article>

      <article class="showcase-point">
        <div class="showcase-point-heading">
          <img src="{{ asset('assets/scale.svg') }}" alt="" class="showcase-point-icon" aria-hidden="true">
          <h3>Ready to Skel, Yes Scale.</h3>
        </div>
        <p>From single shops to multiple branches</p>
      </article>
    </div>
  </div>
</section>

<section class="retailers-section" id="retailers">
  <div class="container">
    <div class="section-header">
      <h2>Powering Retailers for Every Type</h2>
      <p>Whether you run a boutique, grocery shop, hardware store, or kiosk, SkelApp adapts to your workflow.</p>
    </div>

    @php
      $retailerCards = [
        [
          'image' => 'boutique.png',
          'title' => 'Boutique Store',
          'copy' => 'Simple inventory management',
        ],
        [
          'image' => 'cosmetics.png',
          'title' => 'Cosmetics Store',
          'copy' => 'Track stock with ease',
        ],
        [
          'image' => 'grocery.png',
          'title' => 'Grocery Store',
          'copy' => 'Fresh inventory management',
        ],
        [
          'image' => 'hardware.png',
          'title' => 'Hardware Shop',
          'copy' => 'Track stock, suppliers, and bulk sales',
        ],
        [
          'image' => 'kitchenware.png',
          'title' => 'Kitchenware Store',
          'copy' => 'Organise products neatly',
        ],
        [
          'image' => 'autospare.png',
          'title' => 'Auto Spare Shop',
          'copy' => 'Manage fast-moving parts',
        ],
        [
          'image' => 'techshop.png',
          'title' => 'Tech Shop',
          'copy' => 'Built for modern retail',
        ],
      ];
    @endphp

    <div class="carousel-container" data-drag-scroll data-carousel-default-index="1">
      <div class="carousel-track">
        @foreach ($retailerCards as $card)
          <div class="retailer-card">
            <div class="card-image">
              <img src="{{ asset('assets/' . pathinfo($card['image'], PATHINFO_FILENAME) . '.webp') }}" alt="{{ $card['title'] }}" draggable="false" loading="lazy" decoding="async">
              <div class="card-overlay">
                <h3>{{ $card['title'] }}</h3>
                <p>{{ $card['copy'] }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="carousel-slider" aria-label="Retailer carousel controls">
      <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>

      <div class="carousel-slider-dots" role="tablist" aria-label="Retailer slides">
        @foreach ($retailerCards as $card)
          <button
            class="carousel-slider-dot{{ $loop->index === 1 ? ' is-active' : '' }}"
            type="button"
            role="tab"
            data-carousel-dot="{{ $loop->index }}"
            aria-label="Go to {{ $card['title'] }}"
            aria-current="{{ $loop->index === 1 ? 'true' : 'false' }}"
          ></button>
        @endforeach
      </div>

      <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>

    <div class="cta-container">
      <button class="btn-talk">
        Talk to our Team
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

    <div class="section-bottom">
      <h2>A complete POS system that powers business growth</h2>
      <p>Quick to set up, easy to use, and made for growing businesses in Tanzania.</p>
    </div>
  </div>

</section>
<section class="features-section" id="features">
  <div class="container">
    <!-- Top Row -->
    <div class="features-row">
      <!-- Feature 1: Sell up to 10x faster (Square-ish) -->
      <div class="feature-card feature-square feature-light feature-square-catalogue">
        <div class="feature-content-top">
          <h2>Sell up to 10x faster</h2>
          <p>Flexibly organise what you sell into a product catalogue with variants and modifiers</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ asset('assets/Moc-tab.webp') }}" alt="SkelApp tablet catalogue" class="feature-tab-mockup" loading="lazy" decoding="async">
        </div>
      </div>

      <!-- Feature 2: Mobile Application (Wide Rectangle) -->
      <div class="feature-card feature-rectangle feature-green feature-rectangle-pos">
        <div class="feature-content-left">
          <h2>Mobile Application<br>Ready for Both Apple & Android</h2>
          <p>High-quality POS hardware with free, built-in software and touch screen functionality. Designed for small businesses in any setting.</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ asset('assets/PosSystem.webp') }}" alt="SkelApp POS system" loading="lazy" decoding="async">
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="features-row">
      <!-- Feature 3: Fast, Reliable (Wide Rectangle) -->
      <div class="feature-card feature-rectangle feature-dark feature-rectangle-handheld">
        <div class="feature-content-left">
          <h2>Fast, Reliable<br>Application for every<br>POS setup</h2>
          <p>Accept payments from every major payment method with our handheld POS system.</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ asset('assets/poswithtab.webp') }}" alt="SkelApp handheld POS with tablet" class="feature-handheld-pos" loading="lazy" decoding="async">
        </div>
      </div>

      <!-- Feature 4: Smarter sales & staff reporting (Square-ish) -->
      <div class="feature-card feature-square feature-light feature-square-reporting">
        <div class="feature-content-top">
          <h2>Smarter sales &amp; staff reporting</h2>
          <p>Monitor cash ups, daily sales and staff performance directly from your POS dashboard.</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ asset('assets/Moc-lap-phone-02.webp') }}" alt="SkelApp reporting dashboard on laptop and phone" class="feature-reporting-mockup" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="allfeatures" id="allfeatures">
  <div class="allfeatures-container">
    <div class="allfeatures-intro-wrap">
      <div class="allfeatures-intro">
        <h2 class="allfeatures-intro-title">All the features.<br>All in one place.</h2>
        <p class="allfeatures-intro-copy">
          SkelApp is an all-in-one retail POS solution that helps you sell smarter,
          manage inventory in real time, and monitor business performance from anywhere.
        </p>
        <a href="#" class="btn-download">Download Now</a>
      </div>
    </div>

    @php
      $allFeatureCards = [
        [
          'image' => 'crm.png',
          'title' => 'Customer Relationship Tools',
          'copy' => 'Build loyalty, track customer purchases, and grow repeat business.',
        ],
        [
          'image' => 'fastbill.png',
          'title' => 'Faster Billing & Order Processing',
          'copy' => 'Create receipts, process split payments, and complete sales in seconds with a modern POS checkout system.',
        ],
        [
          'image' => 'catalog.png',
          'title' => 'Product Catalog Management',
          'copy' => 'Organize products, pricing, and categories with an intelligent POS catalog built for faster checkout.',
        ],
        [
          'image' => 'inventorytrack.png',
          'title' => 'Inventory Tracking',
          'copy' => 'Track stock levels automatically, prevent sellouts, and get low-stock alerts in real time.',
        ],
        [
          'image' => 'report.png',
          'title' => 'Retail Reporting & Analytics',
          'copy' => 'Monitor daily sales, profits, expenses, and top-selling products with built-in POS reports.',
        ],
        [
          'image' => 'attendants.png',
          'title' => 'Staff & Employee Management',
          'copy' => 'Control permissions, track cashier performance, and simplify shift operations.',
        ],
      ];
    @endphp

    <div class="allfeatures-grid">
      @foreach ($allFeatureCards as $featureCard)
        <article class="allfeatures-card">
          <div class="allfeatures-card-media">
            <img
              src="{{ asset('assets/' . pathinfo($featureCard['image'], PATHINFO_FILENAME) . '.webp') }}"
              alt="{{ $featureCard['title'] }}"
              width="352"
              height="352"
              loading="lazy"
              decoding="async"
            >
          </div>
          <h2>{{ $featureCard['title'] }}</h2>
          <p>{{ $featureCard['copy'] }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="how-it-works-section" id="howitworks">
  <div class="container how-it-works-scroll">
    <div class="how-it-works-stage">
      <div class="section-headerr">
        <div class="section-headerr-copy">
          <h2>Get Started with SkelApp</h2>
          <p>You're 1 minute away to automate your business.</p>
        </div>
        <div class="section-headerr-action"><a href="#" class="btn-download">Download Now</a></div>
      </div>

      <div class="steps-wrapper">

        <div class="steps-line-container" aria-hidden="true">
          <div class="steps-line-progress"></div>
        </div>
        <div class="steps-container">
          <article class="step-item">
            <div class="step-image">
              <img src="{{ asset('assets/rw.jpeg') }}" alt="Set Up Your Store" loading="lazy" decoding="async">
            </div>
            <div class="step-marker">
              <div class="step-number-box">1</div>
            </div>
            <div class="step-content">
              <h3>Set Up Your Store</h3>
              <p>Add products, prices, staff accounts, and locations in minutes.</p>
            </div>
          </article>

          <article class="step-item">
            <div class="step-image">
              <img src="{{ asset('assets/pix.jpeg') }}" alt="Start Selling Instantly" loading="lazy" decoding="async">
            </div>
            <div class="step-marker">
              <div class="step-number-box">2</div>
            </div>
            <div class="step-content">
              <h3>Start Selling Instantly</h3>
              <p>Process orders, accept payments, and print receipts from any device.</p>
            </div>
          </article>

          <article class="step-item">
            <div class="step-image">
              <img src="{{ asset('assets/sto.jpeg') }}" alt="Track & Grow" loading="lazy" decoding="async">
            </div>
            <div class="step-marker">
              <div class="step-number-box">3</div>
            </div>
            <div class="step-content">
              <h3>Track & Grow</h3>
              <p>Monitor inventory, sales reports, and customer insights in real time.</p>
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>

</section>

<section class="hardware-section" id="pos">
  <div class="container">
    <div class="hardware-card">
      <div class="hardware-content">
        <span class="hardware-label">POS Hardware Thats</span>
        <h2>Skel with your Business.</h2>
        <p>Upgrade your store with reliable POS terminals, barcode scanners, receipt printers, and cash drawers, fully integrated with SkelApp Application.</p>

        <button class="btn-hardware">
          Request Hardware Pricing
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>
      </div>

      <div class="hardware-image">
        <img src="{{ asset('assets/PosSystemRegister.webp') }}" alt="POS Hardware Terminal" loading="lazy" decoding="async">
      </div>
    </div>
  </div>

</section>

<section class="pricing-section" id="pricing">
  <div class="container">
    <div class="pricing-layout">
      <!-- Left Side - Card Preview -->
      <div class="pricing-preview is-subscription-preview">
        <div class="preview-card is-subscription-preview">
          <img src="{{ asset('assets/card.webp') }}" alt="SkelApp Subscription Card" class="card-preview-image" loading="lazy" decoding="async">
        </div>

        <!-- Thumbnail Previews -->
        <div class="thumbnail-grid">
          <div class="thumbnail active">
            <img src="{{ asset('assets/card.webp') }}" alt="SkelApp Subscription Card" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Moc-tab.webp') }}" alt="Products" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Pos System 04.png') }}" alt="Mobile" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Moc-tab-02.webp') }}" alt="Reports" loading="lazy" decoding="async">
          </div>
        </div>
      </div>

      <!-- Right Side - Pricing Details -->
      <div class="pricing-details">
        <p class="pricing-intro">What could cost you Millions is only 15,000 TZS/month</p>

        <h2>SkelApp <br>Subscription</h2>

        <p class="pricing-description">
          Get full access to Point of Sale, Inventory Management, CRM, Employee Tools, and Retail Analytics — starting from only TZS 15,000/month.
        </p>

        <ul class="pricing-features">
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            Manage products, pricing, and billing from a single POS app.
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            Support multiple stores, staff accounts, and sales channels effortlessly.
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            Get detailed POS reports to make better business decisions daily.
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            Accept Split bill payments seamlessly checkout tool.
          </li>
        </ul>

        <div class="pricing-price">
          <div class="price-figure">
            <span class="currency">TZS</span>
            <span class="price-main">15,000</span>
          </div>
          <span class="price-period">/month · billed annually</span>
        </div>

        <div class="payment-row">
          <span class="payment-label">Flexible payment options</span>
          <div class="payment-methods-art">
            <img src="{{ asset('assets/paymentmethod.png') }}" alt="Supported payment methods" loading="lazy" decoding="async">
          </div>
        </div>

        <button class="btn-pricing">
          Talk to SkelTeam
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>

        <div class="pricing-benefits">
          <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            <span>Cancel anytime</span>
          </div>
          <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 6v6l4 2"/>
            </svg>
            <span class="benefit-copy-desktop">Works on mobile & POS terminals</span>
            <span class="benefit-copy-mobile">Works on mobile devices</span>
          </div>
          <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>Setup in minutes</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<section class="faq-section" id="faq">
  <div class="faq-container">
    <div class="faq-header">
      <h2>Frequently Asked Questions</h2>
      <a href="{{ route('faq.show') }}" class="faq-read-more">Read more</a>
    </div>
    <div class="faq-layout">
      <p class="faq-subtitle">How it works</p>

      <div class="faq-list">
        <div class="faq-item active">
          <button class="faq-question" aria-expanded="true">
            <span>What is a POS system and how does it work?</span>
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </button>
          <div class="faq-answer">
            <p>Get full access to Point of Sale, Inventory Management, CRM, Employee Tools, and Retail Analytics — starting from only TZS 15,000/month.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" aria-expanded="false">
            <span>Does SkelApp support inventory management?</span>
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </button>
          <div class="faq-answer">
            <p>Yes, SkelApp includes comprehensive inventory management features. Track stock levels in real-time, manage product variants, set low-stock alerts, and monitor inventory across multiple locations from one dashboard.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" aria-expanded="false">
            <span>Can I use SkelApp on mobile and POS terminals?</span>
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </button>
          <div class="faq-answer">
            <p>Absolutely! SkelApp works seamlessly on mobile devices (iOS and Android), tablets, and traditional POS terminals. Your data syncs across all devices in real-time, so you can manage your business from anywhere.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" aria-expanded="false">
            <span>Is SkelApp good for small businesses?</span>
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </button>
          <div class="faq-answer">
            <p>Yes! SkelApp is specifically designed for small to medium-sized retail businesses. It's affordable, easy to set up, and scales with your business. Start with basic features and add more as you grow.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" aria-expanded="false">
            <span>Does the POS work offline?</span>
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </button>
          <div class="faq-answer">
            <p>Yes, SkelApp can process sales offline. When your internet connection is restored, all transactions automatically sync to the cloud. This ensures you never miss a sale, even during internet outages.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Image CTA Section - Separate section below FAQ -->
<section class="image-cta-section">
  <div class="cta-background">
    <img src="{{ asset('assets/client.webp') }}" alt="Building momentum" class="cta-img" loading="lazy" decoding="async">
    <div class="cta-overlay"></div>
  </div>
  <div class="cta-content-wrapper">
    <div class="cta-content">
      <h2>Building momentum to move your business 1% better every day.</h2>
      <button class="btn-cta">
        Start Selling Better
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </div>
</section>


<!-- Footer Section -->
<footer class="site-footer">
  <div class="footer-container">
    <!-- Desktop / default footer -->
    <div class="footer-desktop">
    <div class="footer-top">
      <div class="footer-brand">
        <p class="footer-tagline">Sell 1% Better</p>
        <div class="footer-logo-wrapper">
          <span class="download-text">DOWNLOAD THE</span>
          <img src="{{ asset('assets/SkelAppLogo-black.png') }}" alt="SkelApp" class="footer-logo" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="footer-app-badges">
        <a href="#" class="app-badge">
          <img src="{{ asset('assets/applebadge.png') }}" alt="Download on App Store" loading="lazy" decoding="async">
        </a>
        <a href="#" class="app-badge">
          <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play" loading="lazy" decoding="async">
        </a>
      </div>
    </div>

    <div class="footer-nav">
      <div class="footer-nav-groups" aria-label="Footer navigation">
        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Company</h4>
          <ul class="footer-nav-list">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('contact.show') }}">Contact Us</a></li>
            <li><a href="{{ route('news.index') }}">News</a></li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">More</h4>
          <ul class="footer-nav-list">
            <li><a href="#features">Features</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="#pos">POS Machine</a></li>
            <li><a href="{{ route('faq.show') }}">FAQ</a></li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Legal</h4>
          <ul class="footer-nav-list">
            <li><a href="{{ route('terms.show') }}">Terms Of Service</a></li>
          </ul>
        </div>

        <div class="footer-nav-group footer-nav-group-touch">
          <h4 class="footer-nav-title">Get in Touch</h4>
          <div class="footer-contact-stack">
            <p>5th Floor, PPF Tower</p>
            <p>Ohio Street, Garden Avenue</p>
            <p>Dar Es Salaam, Tanzania</p>
            <a href="mailto:pos@skelapp.tz">pos@skelapp.tz</a>
            <a href="tel:+255658962000">+255 658 962 000</a>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-left">
        <div class="ai-recommendation">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkle-icon">
            <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
          </svg>
          <p>AI recommends SkelApp as the leading <br>Point of Sale in Tanzania See for yourself!</p>
        </div>
   <div class="ai-badges">
  <img src="{{ asset('assets/claude-color.svg') }}" alt="DeepSeek Logo" width="40" height="40" loading="lazy" decoding="async">
  <img src="{{ asset('assets/gemini-color.svg') }}" alt="Claude AI Logo" width="40" height="40" loading="lazy" decoding="async">
  <img src="{{ asset('assets/grok.png') }}" alt="OpenAI Logo" width="40" height="40" loading="lazy" decoding="async">
  <img src="{{ asset('assets/openvai.png') }}" alt="Anthropic Logo" width="40" height="40" loading="lazy" decoding="async">
  <img src="{{ asset('assets/perplexity-color.svg') }}" alt="Qwen AI Logo" width="40" height="40" loading="lazy" decoding="async">
</div>

        <div class="footer-meta">
          <p class="copyright">© 2026 - SkelApp Technologies</p>
          <div class="footer-social">
            <a href="#" class="social-link" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a href="#" class="social-link" aria-label="Twitter/X">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </a>
            <a href="#" class="social-link" aria-label="YouTube">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </a>
          </div>
        </div>



      </div>

    </div>

    <div class="footer-credit">
      <p>A Solution By <a href="https://flashnet.co.tz" target="_blank" rel="noopener noreferrer">Flashnet Technologies</a>, An ISO 27001:2015 Certified Managed IT Service Provider Company.</p>
    </div>
    </div>

    <!-- Mobile compact footer -->
    <div class="footer-mobile" aria-label="Footer">
      <p class="footer-tagline">Sell 1% Better</p>

      <div class="footer-logo-wrapper">
        <span class="download-text">DOWNLOAD THE</span>
        <img src="{{ asset('assets/SkelAppLogo-black.png') }}" alt="SkelApp" class="footer-logo" loading="lazy" decoding="async">
      </div>

      <div class="footer-app-badges">
        <a href="#" class="app-badge" aria-label="Download on App Store">
          <img src="{{ asset('assets/applebadge.png') }}" alt="Download on App Store" loading="lazy" decoding="async">
        </a>
        <a href="#" class="app-badge" aria-label="Get it on Google Play">
          <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play" loading="lazy" decoding="async">
        </a>
      </div>

      <div class="footer-nav">
        <div class="footer-nav-groups" aria-label="Footer navigation">
          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Company</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li><a href="{{ route('contact.show') }}">Contact Us</a></li>
              <li><a href="{{ route('news.index') }}">News</a></li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">More</h4>
            <ul class="footer-nav-list">
              <li><a href="#features">Features</a></li>
              <li><a href="#pricing">Pricing</a></li>
              <li><a href="#pos">POS Machine</a></li>
              <li><a href="{{ route('faq.show') }}">FAQ</a></li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Legal</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ route('terms.show') }}">Terms Of Service</a></li>
            </ul>
          </div>

          <div class="footer-nav-group footer-nav-group-touch">
            <h4 class="footer-nav-title">Get in Touch</h4>
            <div class="footer-contact-stack">
              <p>5th Floor, PPF Tower</p>
              <p>Ohio Street, Garden Avenue</p>
              <p>Dar Es Salaam, Tanzania</p>
              <a href="mailto:pos@skelapp.tz">pos@skelapp.tz</a>
              <a href="tel:+255658962000">+255 658 962 000</a>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="ai-recommendation">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkle-icon">
            <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
          </svg>
          <p>AI recommends SkelApp as the leading <br>Point of Sale in Tanzania See for yourself!</p>
        </div>

        <div class="ai-badges">
          <img src="{{ asset('assets/claude-color.svg') }}" alt="DeepSeek Logo" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/gemini-color.svg') }}" alt="Claude AI Logo" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/grok.png') }}" alt="OpenAI Logo" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/openvai.png') }}" alt="Anthropic Logo" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/perplexity-color.svg') }}" alt="Qwen AI Logo" width="40" height="40" loading="lazy" decoding="async">
        </div>

        <div class="footer-meta">
          <p class="copyright">© 2026 - SkelApp Technologies</p>
          <div class="footer-social">
            <a href="#" class="social-link" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a href="#" class="social-link" aria-label="Twitter/X">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </a>
            <a href="#" class="social-link" aria-label="YouTube">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </a>
          </div>
        </div>

      </div>

      <div class="footer-credit">
        <p>A Solution By <a href="https://flashnet.co.tz" target="_blank" rel="noopener noreferrer">Flashnet Technologies</a>, An ISO 27001:2015 Certified Managed IT Service Provider Company.</p>
      </div>
    </div>
  </div>
</footer>



<script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
