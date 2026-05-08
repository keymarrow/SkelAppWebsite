(() => {
  /* ─── Utility ─────────────────────────────────────────── */
  const onReady = (callback) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback, { once: true });
      return;
    }
    callback();
  };

  const scriptLoads = new Map();
  const loadScript = (src) => {
    if (scriptLoads.has(src)) {
      return scriptLoads.get(src);
    }

    const promise = new Promise((resolve, reject) => {
      const existing = document.querySelector(`script[src="${src}"]`);
      if (existing) {
        if (existing.dataset.loaded === 'true') {
          resolve();
          return;
        }

        existing.addEventListener('load', () => resolve(), { once: true });
        existing.addEventListener('error', () => reject(new Error(`Failed to load ${src}`)), { once: true });
        return;
      }

      const script = document.createElement('script');
      script.src = src;
      script.async = true;
      script.dataset.loaded = 'false';
      script.addEventListener('load', () => {
        script.dataset.loaded = 'true';
        resolve();
      }, { once: true });
      script.addEventListener('error', () => reject(new Error(`Failed to load ${src}`)), { once: true });
      document.head.appendChild(script);
    });

    scriptLoads.set(src, promise);
    return promise;
  };

  const shouldLoadScrollLibraries = () => {
    const hasScrollStory = document.querySelector('.allfeatures, .how-it-works-section');
    if (!hasScrollStory) return false;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;
    return window.matchMedia('(min-width: 901px)').matches;
  };

  const loadScrollLibraries = async () => {
    if (window.gsap && window.ScrollTrigger) return true;

    try {
      await loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js');
      await loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js');
      return Boolean(window.gsap && window.ScrollTrigger);
    } catch {
      return false;
    }
  };

  /* ─── FAQ Accordion ───────────────────────────────────── */
  const initFaqAccordion = () => {
    const faqItems = Array.from(document.querySelectorAll('.faq-item'));

    faqItems.forEach((item) => {
      const question = item.querySelector('.faq-question');
      if (!question) return;

      question.addEventListener('click', () => {
        const isActive = item.classList.contains('active');

        faqItems.forEach((other) => {
          if (other === item) return;
          other.classList.remove('active');
          other.querySelector('.faq-question')?.setAttribute('aria-expanded', 'false');
        });

        item.classList.toggle('active', !isActive);
        question.setAttribute('aria-expanded', String(!isActive));
      });
    });
  };

  /* ─── Drag-Scroll Carousel ────────────────────────────── */
  const initDragScroll = () => {
    document.querySelectorAll('[data-drag-scroll]').forEach((container) => {
      let isDragging = false;
      let startX = 0;
      let startScrollLeft = 0;
      const initialScrollOffset = window.matchMedia('(max-width: 768px)').matches ? 0 : 167.65;

      const stopDragging = () => {
        isDragging = false;
        container.classList.remove('is-dragging');
      };

      window.requestAnimationFrame(() => {
        container.scrollLeft = initialScrollOffset;
      });

      container.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        startScrollLeft = container.scrollLeft;
        container.classList.add('is-dragging');
      });

      container.addEventListener('mouseleave', stopDragging);
      window.addEventListener('mouseup', stopDragging);
      container.addEventListener('dragstart', (e) => e.preventDefault());

      container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        container.scrollLeft = startScrollLeft - (e.pageX - startX);
      });
    });
  };

  /* ─── Retailer Carousel Controls ─────────────────────── */
  const initRetailerCarouselSlider = () => {
    document.querySelectorAll('.carousel-slider').forEach((slider) => {
      const section = slider.closest('.retailers-section');
      const container = section?.querySelector('.carousel-container');
      const cards = container ? Array.from(container.querySelectorAll('.retailer-card')) : [];
      const prevButton = slider.querySelector('[data-carousel-prev]');
      const nextButton = slider.querySelector('[data-carousel-next]');
      const dots = Array.from(slider.querySelectorAll('[data-carousel-dot]'));
      const defaultIndex = Number.parseInt(container?.dataset.carouselDefaultIndex ?? '0', 10) || 0;

      if (!container || !cards.length || !prevButton || !nextButton || !dots.length) return;

      const getTargetScrollLeft = (card) => {
        const centeredOffset = card.offsetLeft - ((container.clientWidth - card.clientWidth) / 2);
        return Math.max(0, centeredOffset);
      };

      const getActiveIndex = () => {
        let closestIndex = 0;
        let smallestDistance = Number.POSITIVE_INFINITY;

        cards.forEach((card, index) => {
          const distance = Math.abs(container.scrollLeft - getTargetScrollLeft(card));
          if (distance < smallestDistance) {
            smallestDistance = distance;
            closestIndex = index;
          }
        });

        return closestIndex;
      };

      const setActiveState = (index) => {
        dots.forEach((dot, dotIndex) => {
          const isActive = dotIndex === index;
          dot.classList.toggle('is-active', isActive);
          dot.setAttribute('aria-current', String(isActive));
        });

        prevButton.disabled = index === 0;
        nextButton.disabled = index === cards.length - 1;
      };

      const scrollToIndex = (index, behavior = 'smooth') => {
        const clampedIndex = Math.max(0, Math.min(index, cards.length - 1));
        container.scrollTo({
          left: getTargetScrollLeft(cards[clampedIndex]),
          behavior,
        });
        setActiveState(clampedIndex);
      };

      prevButton.addEventListener('click', () => {
        scrollToIndex(getActiveIndex() - 1);
      });

      nextButton.addEventListener('click', () => {
        scrollToIndex(getActiveIndex() + 1);
      });

      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          scrollToIndex(index);
        });
      });

      let ticking = false;
      container.addEventListener('scroll', () => {
        if (ticking) return;

        ticking = true;
        window.requestAnimationFrame(() => {
          setActiveState(getActiveIndex());
          ticking = false;
        });
      }, { passive: true });

      window.addEventListener('resize', () => {
        setActiveState(getActiveIndex());
      });

      window.requestAnimationFrame(() => {
        scrollToIndex(defaultIndex, 'auto');
      });
    });
  };

  /* ─── Mobile Nav ──────────────────────────────────────── */
  const initMobileNav = () => {
    const nav = document.querySelector('nav');
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileLinks = document.querySelectorAll('.mobile-menu-container a');

    if (!nav || !mobileToggle) return;

    const closeMenu = () => {
      nav.classList.remove('mobile-active');
      mobileToggle.setAttribute('aria-expanded', 'false');
    };

    mobileToggle.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('mobile-active');
      mobileToggle.setAttribute('aria-expanded', String(isOpen));
    });

    mobileLinks.forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('click', (e) => {
      if (!nav.classList.contains('mobile-active')) return;
      if (nav.contains(e.target)) return;
      closeMenu();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });
  };

  /* ─── How It Works ────────────────────────────────────────
   *
   * Desktop behavior:
   * - Step 1 is visible as soon as the section enters.
   * - The section pins while scroll progress advances the green line.
   * - When progress reaches step 2, step 2 reveals.
   * - When progress reaches step 3, step 3 reveals.
   * - After the final reveal, normal page scrolling continues.
   *
   * Tablet/mobile behavior:
   * - No pinning.
   * - All steps stay visible in the regular stacked/grid layout.
   *
   * ─────────────────────────────────────────────────────── */
  const initHowItWorksScrollStory = () => {
    const section = document.querySelector('.how-it-works-section');
    const scrollShell = section?.querySelector('.how-it-works-scroll');
    const steps   = section ? Array.from(section.querySelectorAll('.step-item')) : [];
    const stage   = section?.querySelector('.how-it-works-stage');
    const markers = steps.map((step) => step.querySelector('.step-number-box')).filter(Boolean);
    const stepMeta = steps.map((step) => ({
      step,
      frame: step.querySelector('.step-image'),
      image: step.querySelector('.step-image img'),
      marker: step.querySelector('.step-marker'),
      content: step.querySelector('.step-content'),
    }));
    const line     = section?.querySelector('.steps-line-container');
    const progress = section?.querySelector('.steps-line-progress');
    const lineWrapper = line?.parentElement;

    if (!section || !scrollShell || !stage || stepMeta.length !== 3) return;

    const revealStates = stepMeta.map(({ frame, image, marker, content }) => [frame, image, marker, content].filter(Boolean));
    revealStates.flat().forEach((el) => { el.style.willChange = 'opacity, transform, clip-path, filter'; });

    if (!window.gsap || !window.ScrollTrigger) {
      stepMeta.forEach(({ frame, image, marker, content }) => {
        [frame, image, marker, content].filter(Boolean).forEach((el) => {
          el.style.opacity = '1';
          el.style.visibility = 'inherit';
          el.style.transform = 'none';
          el.style.clipPath = 'none';
          el.style.filter = 'none';
        });
      });
      return;
    }

    const { gsap, ScrollTrigger } = window;
    gsap.registerPlugin(ScrollTrigger);
    const mm = gsap.matchMedia();

    const clamp01 = gsap.utils.clamp(0, 1);

    const syncLinePosition = () => {
      if (!line || !lineWrapper || markers.length < 2) {
        return { secondRatio: 0.5, thirdRatio: 1 };
      }

      const wrapperRect = lineWrapper.getBoundingClientRect();
      const firstRect = markers[0].getBoundingClientRect();
      const secondRect = markers[1].getBoundingClientRect();
      const lastRect = markers[markers.length - 1].getBoundingClientRect();
      const lineHeight = line.offsetHeight || 2;
      const viewportWidth = window.innerWidth || document.documentElement.clientWidth || wrapperRect.width;
      const secondCenterX = secondRect.left + (secondRect.width / 2);
      const lastCenterX = lastRect.left + (lastRect.width / 2);
      const markerCenterY = firstRect.top + (firstRect.height / 2);
      const secondRatio = clamp01(secondCenterX / Math.max(1, viewportWidth));
      const thirdRatio = clamp01(lastCenterX / Math.max(1, viewportWidth));

      line.style.left = `${-wrapperRect.left}px`;
      line.style.top = `${markerCenterY - wrapperRect.top - (lineHeight / 2)}px`;
      line.style.width = `${viewportWidth}px`;

      return { secondRatio, thirdRatio };
    };

const renderStepState = (state, amount) => {
  const p = clamp01(amount);

  // Image card: starts 80px ABOVE its final position, falls down to 0
  const frameY = -80 * (1 - p);

  // Image inner: subtle scale for depth feel
  const imageScale = 1.06 - (0.06 * p);

  // Content (number badge + text): starts 48px BELOW, rises up to 0
  const contentY = 48 * (1 - p);

  if (state.frame) {
    gsap.set(state.frame, {
      autoAlpha: p,
      y: frameY,
      // NO clipPath — card physically moves, not masked
    });
  }

  if (state.image) {
    gsap.set(state.image, {
      scale: imageScale,
      filter: 'none',
      yPercent: 0,
    });
  }

  [state.marker, state.content].filter(Boolean).forEach((el) => {
    gsap.set(el, {
      autoAlpha: p,
      y: contentY,
    });
  });
};
    const showAllSteps = () => {
      stepMeta.forEach((state) => renderStepState(state, 1));
      if (line && progress) {
        gsap.set(line, { autoAlpha: 1 });
        gsap.set(progress, { scaleX: 1, transformOrigin: 'left center' });
      }
    };

    /* ── Desktop (≥1201 px) ── */
    mm.add('(min-width: 1201px) and (prefers-reduced-motion: no-preference)', () => {
      const scrollDistance = () => Math.max(window.innerHeight * 1.2, 960);
      let secondTrigger = 0.5;
      let thirdTrigger = 0.9;
      let sectionEntryTrigger = 0.3;
      const revealWindow = 0.12;
      let trigger = null;

      const syncDesktopMetrics = () => {
        scrollShell.style.setProperty('--how-it-works-stage-height', `${stage.offsetHeight}px`);
        scrollShell.style.setProperty('--how-it-works-progress-space', `${scrollDistance()}px`);
        const lineMetrics = syncLinePosition();
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
        const triggerDistance = Math.max(section.offsetHeight, 1);
        secondTrigger = clamp01(lineMetrics.secondRatio);
        thirdTrigger = clamp01(lineMetrics.thirdRatio);
        sectionEntryTrigger = clamp01(viewportHeight / triggerDistance);
      };

      const applyDesktopProgress = (progressValue) => {
        const rawProgress = clamp01(progressValue);
        const safeEntryTrigger = Math.min(0.95, Math.max(sectionEntryTrigger, 0.0001));
        const scrollProgress = rawProgress <= safeEntryTrigger
          ? clamp01((rawProgress / safeEntryTrigger) * secondTrigger)
          : clamp01(secondTrigger + (((rawProgress - safeEntryTrigger) / Math.max(1 - safeEntryTrigger, 0.0001)) * (1 - secondTrigger)));
        const secondReveal = clamp01((scrollProgress - secondTrigger) / revealWindow);
        const thirdReveal = clamp01((scrollProgress - (thirdTrigger - revealWindow)) / revealWindow);

        if (progress) {
          gsap.set(progress, { scaleX: scrollProgress });
        }

        renderStepState(stepMeta[0], 1);
        renderStepState(stepMeta[1], secondReveal);
        renderStepState(stepMeta[2], thirdReveal);
      };

      renderStepState(stepMeta[0], 1);
      renderStepState(stepMeta[1], 0);
      renderStepState(stepMeta[2], 0);

      if (line && progress) {
        gsap.set(line, { autoAlpha: 1 });
        gsap.set(progress, { scaleX: 0, transformOrigin: 'left center' });
      }

      const handleRefresh = () => {
        syncDesktopMetrics();
        applyDesktopProgress(trigger ? trigger.progress : 0);
      };

      syncDesktopMetrics();
      ScrollTrigger.addEventListener('refreshInit', handleRefresh);

      trigger = ScrollTrigger.create({
        trigger: section,
        start: 'top bottom',
        end: 'bottom bottom',
        scrub: 0.45,
        invalidateOnRefresh: true,
        onUpdate: (self) => applyDesktopProgress(self.progress),
        onLeave: () => applyDesktopProgress(1),
        onLeaveBack: () => applyDesktopProgress(0),
        onRefresh: (self) => applyDesktopProgress(self.progress),
      });

      applyDesktopProgress(trigger.progress);

      return () => {
        trigger.kill();
        ScrollTrigger.removeEventListener('refreshInit', handleRefresh);
        scrollShell.style.removeProperty('--how-it-works-stage-height');
        scrollShell.style.removeProperty('--how-it-works-progress-space');
        gsap.set([...revealStates.flat(), line, progress].filter(Boolean), { clearProps: 'all' });
      };
    });

    /* ── Tablet & Mobile (≤1200 px) ── */
    mm.add('(max-width: 1200px) and (prefers-reduced-motion: no-preference)', () => {
      // Initialize steps
      stepMeta.forEach((state) => renderStepState(state, 0));
      if (progress) gsap.set(progress, { scaleY: 0, transformOrigin: 'top center' });
      if (line) gsap.set(line, { autoAlpha: 1 });

      const trigger = ScrollTrigger.create({
        trigger: section,
        start: 'top 75%',
        end: 'bottom 25%',
        scrub: 0.45,
        onUpdate: (self) => {
          const p = self.progress;
          if (progress) gsap.set(progress, { scaleY: p });
          
          // Reveal steps based on progress
          renderStepState(stepMeta[0], clamp01(p * 3));
          renderStepState(stepMeta[1], clamp01((p - 0.33) * 3));
          renderStepState(stepMeta[2], clamp01((p - 0.66) * 3));
        }
      });

      return () => {
        trigger.kill();
        scrollShell.style.removeProperty('--how-it-works-stage-height');
        scrollShell.style.removeProperty('--how-it-works-progress-space');
        gsap.set([...revealStates.flat(), line, progress].filter(Boolean), { clearProps: 'all' });
      };
    });

    /* ── Reduced motion: just show everything ── */
    mm.add('(prefers-reduced-motion: reduce)', () => {
      showAllSteps();

      return () => {
        scrollShell.style.removeProperty('--how-it-works-stage-height');
        scrollShell.style.removeProperty('--how-it-works-progress-space');
        gsap.set([...revealStates.flat(), line, progress].filter(Boolean), { clearProps: 'all' });
      };
    });
  };

  /* ─── All Features Cards ──────────────────────────────── */
  const initAllFeaturesScrollStory = () => {
    const section = document.querySelector('.allfeatures');
    const cards   = section ? Array.from(section.querySelectorAll('.allfeatures-card')) : [];

    if (!section || cards.length === 0) return;

    // Safety net
    cards.forEach((c) => { c.style.willChange = 'opacity, transform'; });

    if (!window.gsap || !window.ScrollTrigger) {
      cards.forEach((c) => { c.style.opacity = '1'; c.style.transform = 'none'; });
      return;
    }

    const { gsap, ScrollTrigger } = window;
    gsap.registerPlugin(ScrollTrigger);

    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
      gsap.set(cards, { autoAlpha: 0, y: 44 });

      const anims = cards.map((card, i) =>
        gsap.to(card, {
          autoAlpha: 1,
          y:         0,
          duration:  0.68,
          ease:      'power2.out',
          // Stagger cards in the same column-pair slightly
          delay:     (i % 2) * 0.1,
          scrollTrigger: {
            trigger: card,
            start:   'top 88%',
            once:    true,
          },
        })
      );

      return () => {
        anims.forEach((a) => { a.scrollTrigger?.kill(); a.kill(); });
        gsap.set(cards, { clearProps: 'all' });
      };
    });

    mm.add('(prefers-reduced-motion: reduce)', () => {
      gsap.set(cards, { clearProps: 'all' });
    });
  };

  /* ─── Pricing thumbnail switcher ─────────────────────── */
  const initPricingThumbnails = () => {
    const preview    = document.querySelector('.card-preview-image');
    const thumbnails = Array.from(document.querySelectorAll('.pricing-section .thumbnail'));
    const card = preview?.closest('.preview-card');
    const wrap = preview?.closest('.pricing-preview');

    if (!preview || thumbnails.length === 0) return;

    const isSubscriptionSrc = (src) =>
      typeof src === 'string' &&
      (
        src.includes('/card.png') ||
        src.endsWith('card.png') ||
        src.includes('/card.webp') ||
        src.endsWith('card.webp')
      );

    const updatePreviewMode = (src) => {
      const isSubscription = isSubscriptionSrc(src);
      card?.classList.toggle('is-subscription-preview', isSubscription);
      card?.classList.toggle('is-media-preview', !isSubscription);
      wrap?.classList.toggle('is-subscription-preview', isSubscription);
      wrap?.classList.toggle('is-media-preview', !isSubscription);
      if (!isSubscription) {
        preview.style.transform = 'none';
      }
    };

    updatePreviewMode(preview.getAttribute('src') || '');

    thumbnails.forEach((thumb) => {
      thumb.addEventListener('click', () => {
        const thumbImage = thumb.querySelector('img');
        const src = thumbImage?.getAttribute('src');
        if (!src) return;

        thumbnails.forEach((t) => t.classList.remove('active'));
        thumb.classList.add('active');
        preview.setAttribute('src', src);
        preview.setAttribute('alt', thumbImage?.getAttribute('alt') || 'SkelApp preview');
        updatePreviewMode(src);
      });
    });
  };

  const initPricingCardTilt = () => {
    const canTilt =
      window.matchMedia('(hover: hover) and (pointer: fine)').matches &&
      window.matchMedia('(prefers-reduced-motion: no-preference)').matches;

    if (!canTilt) return;

    const wrap = document.querySelector('.pricing-section .pricing-preview');
    const card = document.querySelector('.pricing-section .preview-card');
    const img  = card?.querySelector('.card-preview-image');
    if (!wrap || !card || !img) return;

    const isSubscriptionPreview = () => card.classList.contains('is-subscription-preview');

    /* Shine sits directly over the image — sized + positioned to match it */
    const shine = document.createElement('div');
    shine.className = 'card-shine';
    card.appendChild(shine);

    /* Glow sits on the wrapper, behind everything */
    const glow = document.createElement('div');
    glow.className = 'card-glow';
    wrap.appendChild(glow);

    /* Sync shine size/position to the image element */
    const syncShine = () => {
      const imgRect  = img.getBoundingClientRect();
      const cardRect = card.getBoundingClientRect();
      shine.style.width  = imgRect.width  + 'px';
      shine.style.height = imgRect.height + 'px';
      shine.style.top    = (imgRect.top  - cardRect.top)  + 'px';
      shine.style.left   = (imgRect.left - cardRect.left) + 'px';
    };
    syncShine();
    window.addEventListener('resize', syncShine);

    /* Lerp state */
    let rotX = 0, rotY = 0;
    let shinX = 50, shinY = 50;
    let glowX = 0, glowY = 0;
    let tRotX = 0, tRotY = 0;
    let tShinX = 50, tShinY = 50;
    let tGlowX = 0, tGlowY = 0;

    let rafId = null;
    let active = false;

    const MAX_TILT   = 10;
    const MAX_GLOW   = 30;
    const LERP       = 0.09;
    const lerp = (a, b, t) => a + (b - a) * t;

    const resetPreviewEffects = () => {
      rotX = 0; rotY = 0;
      shinX = 50; shinY = 50;
      glowX = 0; glowY = 0;
      tRotX = 0; tRotY = 0;
      tShinX = 50; tShinY = 50;
      tGlowX = 0; tGlowY = 0;
      active = false;
      img.style.transform = 'none';
      shine.style.backgroundImage = 'transparent';
      glow.style.transform = 'translate(-50%, -50%)';
    };

    const tick = () => {
      rotX  = lerp(rotX,  tRotX,  LERP);
      rotY  = lerp(rotY,  tRotY,  LERP);
      shinX = lerp(shinX, tShinX, LERP);
      shinY = lerp(shinY, tShinY, LERP);
      glowX = lerp(glowX, tGlowX, LERP);
      glowY = lerp(glowY, tGlowY, LERP);

      /* Only the image tilts — container stays flat */
      img.style.transform =
        `perspective(900px) rotateX(${rotX}deg) rotateY(${rotY}deg) scale3d(1.02,1.02,1.02)`;

      /* Shine follows cursor across the image face */
      shine.style.backgroundImage =
        `radial-gradient(circle at ${shinX}% ${shinY}%,
          rgba(255,255,255,0.26) 0%,
          rgba(255,255,255,0.06) 45%,
          transparent 68%)`;

      /* Glow shifts by small px offset — always stays near center */
      glow.style.transform =
        `translate(calc(-50% + ${glowX}px), calc(-50% + ${glowY}px))`;

      const settled =
        Math.abs(rotX  - tRotX)  < 0.02 &&
        Math.abs(rotY  - tRotY)  < 0.02 &&
        Math.abs(glowX - tGlowX) < 0.1  &&
        Math.abs(glowY - tGlowY) < 0.1;

      if (active || !settled) {
        rafId = requestAnimationFrame(tick);
      } else {
        rafId = null;
      }
    };

    /* Listen on the IMAGE, not the card container */
    img.addEventListener('mousemove', (e) => {
      if (!isSubscriptionPreview()) {
        resetPreviewEffects();
        return;
      }

      const rect = img.getBoundingClientRect();
      const pctX = (e.clientX - rect.left) / rect.width;
      const pctY = (e.clientY - rect.top)  / rect.height;

      tRotY  =  (pctX - 0.5) * MAX_TILT * 2;
      tRotX  = -(pctY - 0.5) * MAX_TILT * 2;
      tShinX = pctX * 100;
      tShinY = pctY * 100;
      tGlowX = (pctX - 0.5) * MAX_GLOW * 2;
      tGlowY = (pctY - 0.5) * MAX_GLOW * 2;

      active = true;
      if (!rafId) rafId = requestAnimationFrame(tick);
    });

    img.addEventListener('mouseenter', () => {
      if (!isSubscriptionPreview()) return;
      active = true;
    });

    img.addEventListener('mouseleave', () => {
      if (!isSubscriptionPreview()) {
        resetPreviewEffects();
        return;
      }

      active = false;
      tRotX = 0; tRotY = 0;
      tShinX = 50; tShinY = 50;
      tGlowX = 0; tGlowY = 0;
      if (!rafId) rafId = requestAnimationFrame(tick);
    });
  };

  /* ─── News Article Actions ────────────────────────────── */
  const initNewsArticleActions = () => {
    const shareButton = document.querySelector('.news-share-button');
    const audioButton = document.querySelector('.news-audio-button');
    const audioLabel = audioButton?.querySelector('[data-audio-label]');
    const articleBody = document.querySelector('#article-body');
    const supportsSpeech = 'speechSynthesis' in window && 'SpeechSynthesisUtterance' in window;
    const speech = supportsSpeech ? window.speechSynthesis : null;

    if (shareButton) {
      shareButton.addEventListener('click', async () => {
        const shareData = {
          title: shareButton.dataset.shareTitle,
          url: window.location.href,
        };

        try {
          if (navigator.share) {
            await navigator.share(shareData);
            return;
          }

          await navigator.clipboard.writeText(window.location.href);
          shareButton.textContent = 'Link copied';
          window.setTimeout(() => {
            shareButton.textContent = 'Share';
          }, 1800);
        } catch (error) {
          shareButton.textContent = 'Share';
        }
      });
    }

    if (!audioButton || !audioLabel) return;

    const normalizeNarrationText = (text) => text?.replace(/\s+/g, ' ').trim() ?? '';
    const splitNarrationChunks = (text, maxLength = 320) => {
      const normalizedText = normalizeNarrationText(text);
      if (!normalizedText) return [];

      const sentences = normalizedText.match(/[^.!?]+[.!?]*/g) ?? [normalizedText];
      const chunks = [];
      let currentChunk = '';

      sentences.forEach((sentence) => {
        const normalizedSentence = normalizeNarrationText(sentence);
        if (!normalizedSentence) return;

        const nextChunk = currentChunk ? `${currentChunk} ${normalizedSentence}` : normalizedSentence;
        if (nextChunk.length > maxLength && currentChunk) {
          chunks.push(currentChunk);
          currentChunk = normalizedSentence;
          return;
        }

        currentChunk = nextChunk;
      });

      if (currentChunk) {
        chunks.push(currentChunk);
      }

      return chunks;
    };

    const articleSections = Array.from(articleBody?.querySelectorAll('.news-copy-block') ?? []);
    const articleChunks = articleSections.flatMap((section) => {
      const heading = normalizeNarrationText(section.querySelector('h2')?.textContent);
      const paragraphs = Array.from(section.querySelectorAll('p'))
        .map((paragraph) => normalizeNarrationText(paragraph.textContent))
        .filter(Boolean)
        .join(' ');

      const sectionText = [heading ? `${heading}.` : '', paragraphs].filter(Boolean).join(' ');
      return splitNarrationChunks(sectionText, 420);
    });

    if (!supportsSpeech || articleChunks.length === 0) {
      audioButton.disabled = true;
      audioLabel.textContent = 'Audio unavailable';
      return;
    }

    let isPlaying = false;
    let isStopping = false;
    let chunkIndex = 0;
    let availableVoices = [];
    let stopTimeoutId = null;

    const narratorVoiceMatchers = [
      /google uk english male/i,
      /\bdaniel\b/i,
      /\balex\b/i,
      /\baaron\b/i,
      /\bdavid\b/i,
      /\bthomas\b/i,
      /\boliver\b/i,
      /\barthur\b/i,
      /\bfred\b/i,
      /\bguy\b/i,
      /\bmale\b/i,
      /\bryan\b/i,
      /\bwayne\b/i,
      /\broger\b/i,
    ];

    const resolveNarratorVoice = () => {
      const voices = availableVoices.length > 0 ? availableVoices : speech.getVoices();
      const englishVoices = voices.filter((voice) => voice.lang?.toLowerCase().startsWith('en'));

      return (
        englishVoices.find((voice) =>
          narratorVoiceMatchers.some((matcher) => matcher.test(`${voice.name} ${voice.voiceURI}`))
        ) ||
        englishVoices.find((voice) => voice.localService) ||
        englishVoices[0] ||
        voices.find((voice) => voice.default) ||
        voices[0] ||
        null
      );
    };

    const refreshVoices = () => {
      availableVoices = speech.getVoices();
    };

    refreshVoices();
    speech.addEventListener?.('voiceschanged', refreshVoices);

    const setAudioState = (state) => {
      const isActive = state === 'playing';
      audioButton.classList.toggle('is-playing', isActive);
      audioButton.setAttribute('aria-pressed', String(isActive));

      if (state === 'playing') {
        audioLabel.textContent = 'Stop reading';
        audioButton.setAttribute('aria-label', 'Stop reading article');
        return;
      }

      if (state === 'error') {
        audioLabel.textContent = 'Audio unavailable';
        audioButton.setAttribute('aria-label', 'Audio unavailable');
        return;
      }

      audioLabel.textContent = 'Listen to article';
      audioButton.setAttribute('aria-label', 'Listen to article');
    };

    const clearStopTimeout = () => {
      if (stopTimeoutId === null) return;
      window.clearTimeout(stopTimeoutId);
      stopTimeoutId = null;
    };

    const resetAudio = () => {
      clearStopTimeout();
      isPlaying = false;
      isStopping = false;
      chunkIndex = 0;
      setAudioState('idle');
    };

    const stopAudio = () => {
      if (!speech.speaking && !speech.pending && !isPlaying) {
        resetAudio();
        return;
      }

      isStopping = true;
      isPlaying = false;
      chunkIndex = 0;
      setAudioState('idle');
      speech.cancel();
      clearStopTimeout();
      stopTimeoutId = window.setTimeout(() => {
        if (isStopping) {
          resetAudio();
        }
      }, 120);
    };

    const speakChunk = () => {
      if (!isPlaying || chunkIndex >= articleChunks.length) {
        resetAudio();
        return;
      }

      const utterance = new SpeechSynthesisUtterance(articleChunks[chunkIndex]);
      const narratorVoice = resolveNarratorVoice();

      utterance.lang = narratorVoice?.lang || document.documentElement.lang || 'en-US';
      utterance.voice = narratorVoice;
      utterance.rate = 0.88;
      utterance.pitch = 0.82;
      utterance.volume = 1;

      utterance.onstart = () => {
        if (isStopping) return;
        setAudioState('playing');
      };

      utterance.onend = () => {
        if (isStopping) {
          resetAudio();
          return;
        }

        chunkIndex += 1;
        speakChunk();
      };

      utterance.onerror = (event) => {
        if (isStopping || event?.error === 'canceled' || event?.error === 'interrupted') {
          resetAudio();
          return;
        }

        resetAudio();
      };

      speech.speak(utterance);
    };

    audioButton.addEventListener('click', () => {
      if (isPlaying || speech.speaking || speech.pending) {
        stopAudio();
        return;
      }

      speech.cancel();
      isPlaying = true;
      isStopping = false;
      chunkIndex = 0;
      speakChunk();
    });

    window.addEventListener('beforeunload', stopAudio);
  };

  /* ─── Boot ────────────────────────────────────────────── */
  onReady(() => {
    initFaqAccordion();
    initDragScroll();
    initRetailerCarouselSlider();
    initMobileNav();
    initNewsArticleActions();
    initPricingCardTilt();
    initPricingThumbnails();

    const initScrollStories = async () => {
      if (window.__scrollStoriesInitialized) return;
      window.__scrollStoriesInitialized = true;

      if (shouldLoadScrollLibraries()) {
        await loadScrollLibraries();
      }

      initAllFeaturesScrollStory();
      initHowItWorksScrollStory();

      // Give browser one extra frame to finish layout before refresh
      const refresh = () => {
        if (!window.ScrollTrigger) return;
        requestAnimationFrame(() => requestAnimationFrame(() => ScrollTrigger.refresh()));
      };

      refresh();
      document.fonts?.ready.then(refresh);
    };

    const scheduleScrollStories = () => {
      const run = () => {
        void initScrollStories();
      };

      if ('requestIdleCallback' in window) {
        window.requestIdleCallback(run, { timeout: 1200 });
        return;
      }

      window.setTimeout(run, 250);
    };

    if (document.readyState === 'complete') {
      scheduleScrollStories();
    } else {
      window.addEventListener('load', scheduleScrollStories, { once: true });
    }
  });
})();
