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

  const loadFirstAvailableScript = async (sources) => {
    let lastError = null;

    for (const src of sources) {
      try {
        await loadScript(src);
        return true;
      } catch (error) {
        lastError = error;
      }
    }

    if (lastError) throw lastError;
    return false;
  };

  const shouldLoadScrollLibraries = () => {
    const hasScrollStory = document.querySelector('.allfeatures, .how-it-works-section');
    if (!hasScrollStory) return false;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;
    return true;
  };

  const loadScrollLibraries = async () => {
    if (window.gsap && window.ScrollTrigger) return true;

    try {
      await loadFirstAvailableScript([
        '/vendor/gsap/gsap.min.js',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
      ]);
      await loadFirstAvailableScript([
        '/vendor/gsap/ScrollTrigger.min.js',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
      ]);
      return Boolean(window.gsap && window.ScrollTrigger);
    } catch {
      return false;
    }
  };

  /* ─── FAQ Accordion ───────────────────────────────────── */
  const initFaqAccordion = () => {
    const faqItems = Array.from(document.querySelectorAll('.faq-item'));
    const animateFaqItem = (item, shouldExpand) => {
      const question = item.querySelector('.faq-question');
      const answer = item.querySelector('.faq-answer');
      if (!question || !answer) return;

      const currentHeight = answer.getBoundingClientRect().height;
      answer.style.height = `${currentHeight}px`;
      answer.offsetHeight;

      item.classList.toggle('active', shouldExpand);
      question.setAttribute('aria-expanded', String(shouldExpand));

      if (shouldExpand) {
        answer.style.height = `${answer.scrollHeight}px`;
        return;
      }

      answer.style.height = '0px';
    };

    faqItems.forEach((item) => {
      const question = item.querySelector('.faq-question');
      const answer = item.querySelector('.faq-answer');
      if (!question) return;

      question.setAttribute('aria-expanded', String(item.classList.contains('active')));
      if (answer) {
        answer.style.height = item.classList.contains('active') ? 'auto' : '0px';

        answer.addEventListener('transitionend', (event) => {
          if (event.propertyName !== 'height') return;
          answer.style.height = item.classList.contains('active') ? 'auto' : '0px';
        });
      }

      question.addEventListener('click', () => {
        const isActive = item.classList.contains('active');
        const scope = item.closest('[data-faq-accordion-group]') || document;
        const scopedItems = Array.from(scope.querySelectorAll('.faq-item'));

        scopedItems.forEach((other) => {
          if (other === item) return;
          animateFaqItem(other, false);
        });

        animateFaqItem(item, !isActive);
      });
    });
  };

  /* ─── FAQ Page Navigation ─────────────────────────────── */
  const initFaqPageNavigation = () => {
    const faqPage = document.querySelector('.faq-page');
    if (!faqPage) return;

    const links = Array.from(faqPage.querySelectorAll('[data-faq-nav-link]'));
    const sections = Array.from(faqPage.querySelectorAll('[data-faq-page-section]'));
    if (!links.length || !sections.length) return;

    const setActiveLink = (targetId) => {
      links.forEach((link) => {
        const linkTargetId = link.getAttribute('href')?.replace('#', '');
        const isActive = linkTargetId === targetId;
        link.classList.toggle('is-active', isActive);
        link.setAttribute('aria-current', isActive ? 'location' : 'false');
      });
    };

    links.forEach((link) => {
      link.addEventListener('click', () => {
        const targetId = link.getAttribute('href')?.replace('#', '');
        if (targetId) {
          setActiveLink(targetId);
        }
      });
    });

    const initialSection = sections.find((section) => section.id === window.location.hash.slice(1)) || sections[0];
    if (initialSection?.id) {
      setActiveLink(initialSection.id);
    }

    if (!('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries) => {
      const visibleEntries = entries
        .filter((entry) => entry.isIntersecting)
        .sort((first, second) => second.intersectionRatio - first.intersectionRatio);

      if (visibleEntries[0]?.target?.id) {
        setActiveLink(visibleEntries[0].target.id);
      }
    }, {
      rootMargin: '-22% 0px -52% 0px',
      threshold: [0.2, 0.45, 0.7],
    });

    sections.forEach((section) => observer.observe(section));
  };

  /* ─── Drag-Scroll Carousel ────────────────────────────── */
  const initDragScroll = () => {
    document.querySelectorAll('[data-drag-scroll]').forEach((container) => {
      let isDragging = false;
      let hasDragged = false;
      let startX = 0;
      let startY = 0;
      let startScrollLeft = 0;
      let touchAxisLock = null;
      const touchLockThreshold = 8;
      const dragClickThreshold = 5;

      const stopDragging = () => {
        isDragging = false;
        touchAxisLock = null;
        container.classList.remove('is-dragging');
      };

      container.addEventListener('mousedown', (e) => {
        isDragging = true;
        hasDragged = false;
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
        if (Math.abs(e.pageX - startX) > dragClickThreshold) hasDragged = true;
        container.scrollLeft = startScrollLeft - (e.pageX - startX);
      });

      // Suppress link navigation when the pointer was dragging the carousel.
      container.addEventListener('click', (e) => {
        if (hasDragged) {
          e.preventDefault();
          e.stopPropagation();
          hasDragged = false;
        }
      }, true);

      container.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        if (!touch) return;

        isDragging = true;
        touchAxisLock = null;
        startX = touch.clientX;
        startY = touch.clientY;
        startScrollLeft = container.scrollLeft;
      }, { passive: true });

      container.addEventListener('touchmove', (e) => {
        if (!isDragging) return;

        const touch = e.touches[0];
        if (!touch) return;

        const deltaX = touch.clientX - startX;
        const deltaY = touch.clientY - startY;

        if (!touchAxisLock) {
          if (Math.abs(deltaX) < touchLockThreshold && Math.abs(deltaY) < touchLockThreshold) {
            return;
          }

          touchAxisLock = Math.abs(deltaX) > Math.abs(deltaY) ? 'x' : 'y';
          if (touchAxisLock === 'x') {
            container.classList.add('is-dragging');
          }
        }

        if (touchAxisLock !== 'x') return;

        e.preventDefault();
        container.scrollLeft = startScrollLeft - deltaX;
      }, { passive: false });

      container.addEventListener('touchend', stopDragging, { passive: true });
      container.addEventListener('touchcancel', stopDragging, { passive: true });

      // Custom circular "drag" cursor that follows the pointer over the carousel.
      if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        const cursor = document.createElement('div');
        cursor.className = 'carousel-cursor';
        cursor.setAttribute('aria-hidden', 'true');
        cursor.innerHTML =
          '<div class="carousel-cursor-inner">' +
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" ' +
          'stroke-linecap="round" stroke-linejoin="round"><path d="M7 7l10 10M17 9v8h-8"/></svg>' +
          '</div>';
        document.body.appendChild(cursor);

        const moveCursor = (e) => {
          cursor.style.transform = 'translate(' + e.clientX + 'px,' + e.clientY + 'px)';
        };

        container.addEventListener('mouseenter', (e) => {
          moveCursor(e);
          cursor.classList.add('is-visible');
        });
        container.addEventListener('mouseleave', () => {
          cursor.classList.remove('is-visible', 'is-down');
        });
        container.addEventListener('mousemove', moveCursor);
        container.addEventListener('mousedown', () => cursor.classList.add('is-down'));
        window.addEventListener('mouseup', () => cursor.classList.remove('is-down'));
      }
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
      const configuredDefaultIndex = Number.parseInt(container?.dataset.carouselDefaultIndex ?? '0', 10) || 0;
      const defaultIndex = window.matchMedia('(width: 768px), (width: 820px)').matches ? 0 : configuredDefaultIndex;

      if (!container || !cards.length || !prevButton || !nextButton || !dots.length) return;

      const track = container.querySelector('.carousel-track');
      const getLeftInset = () => (track ? parseFloat(getComputedStyle(track).paddingLeft) || 0 : 0);

      const getTargetScrollLeft = (card) => {
        // Align each card to the track's left inset at every breakpoint, so the
        // first card starts flush with the section heading (its left margin
        // stays visible) and every card keeps the same left margin when active.
        return Math.max(0, card.offsetLeft - getLeftInset());
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

  /* ─── How It Works ──────────────────────────────────────── */
  const initHowItWorksScrollStory = () => {
    const section = document.querySelector('.how-it-works-section');
    const scrollShell = section?.querySelector('.how-it-works-scroll');
    const stage = section?.querySelector('.how-it-works-stage');
    const line = section?.querySelector('.steps-line-container');
    const progress = section?.querySelector('.steps-line-progress');
    const lineWrapper = line?.parentElement;
    const steps = section ? Array.from(section.querySelectorAll('.step-item')) : [];

    if (!section || !scrollShell || !stage || !line || !progress || !lineWrapper || steps.length !== 3) return;

    const stepMeta = steps.map((step) => ({
      frame: step.querySelector('.step-image'),
      image: step.querySelector('.step-image img'),
      marker: step.querySelector('.step-marker'),
      number: step.querySelector('.step-number-box'),
      content: step.querySelector('.step-content'),
    }));
    const stepImages = stepMeta.map(({ image }) => image).filter(Boolean);

    const clamp01 = (value) => Math.min(1, Math.max(0, value));
    const isCompactLayout = () => window.matchMedia('(max-width: 743px)').matches;
    const isDesktopLayout = () => window.matchMedia('(min-width: 1201px)').matches;
    const desktopScrollDistance = () => Math.max(window.innerHeight * 1.2, 960);
    const timelineThickness = 2;

    let rafId = null;
    let resizeObserver = null;

    const setDesktopShell = () => {
      scrollShell.style.setProperty('--how-it-works-stage-height', `${stage.offsetHeight}px`);
      scrollShell.style.setProperty('--how-it-works-progress-space', `${desktopScrollDistance()}px`);
    };

    const clearDesktopShell = () => {
      scrollShell.style.removeProperty('--how-it-works-stage-height');
      scrollShell.style.removeProperty('--how-it-works-progress-space');
    };

    const setStepState = (state, amount) => {
      const p = clamp01(amount);
      const frameY = -120 * (1 - p);
      const frameBlur = 14 * (1 - p);
      const contentY = 42 * (1 - p);

      if (state.frame) {
        state.frame.style.opacity = String(p);
        state.frame.style.visibility = p > 0 ? 'visible' : 'hidden';
        state.frame.style.transform = `translate3d(0, ${frameY}px, 0)`;
        state.frame.style.clipPath = 'none';
        state.frame.style.filter = `blur(${frameBlur}px)`;
      }

      if (state.image) {
        state.image.style.transform = 'translate3d(0, 0, 0) scale(1)';
        state.image.style.filter = 'none';
      }

      [state.marker, state.content].filter(Boolean).forEach((el) => {
        el.style.opacity = String(p);
        el.style.visibility = p > 0 ? 'visible' : 'hidden';
        el.style.transform = `translate3d(0, ${contentY}px, 0)`;
      });
    };

    const setAllStepsVisible = () => {
      stepMeta.forEach((state) => setStepState(state, 1));
    };

    const getLineMetrics = () => {
      const numbers = stepMeta.map(({ number }) => number).filter(Boolean);
      if (numbers.length < 2) {
        return { secondRatio: 0.5, thirdRatio: 1 };
      }

      const wrapperRect = lineWrapper.getBoundingClientRect();
      const firstRect = numbers[0].getBoundingClientRect();
      const secondRect = numbers[1].getBoundingClientRect();
      const lastRect = numbers[numbers.length - 1].getBoundingClientRect();

      if (isCompactLayout()) {
        const lineWidth = timelineThickness;
        const firstCenterX = firstRect.left + (firstRect.width / 2);
        const firstCenterY = firstRect.top + (firstRect.height / 2);
        const secondCenterY = secondRect.top + (secondRect.height / 2);
        const lastCenterY = lastRect.top + (lastRect.height / 2);
        const travel = Math.max(1, lastCenterY - firstCenterY);

        // Extend the line from the first marker center to the BOTTOM
        // of the last step (past its image), not just to the last
        // marker center — otherwise the line "breaks" after the
        // last marker and doesn't run through step 3's image area.
        const lastStep = steps[steps.length - 1];
        const lastStepBottom = lastStep
          ? lastStep.getBoundingClientRect().bottom
          : lastCenterY;
        const fullHeight = Math.max(travel, lastStepBottom - firstCenterY);

        line.style.left = `${firstCenterX - wrapperRect.left - (lineWidth / 2)}px`;
        line.style.top = `${firstCenterY - wrapperRect.top}px`;
        line.style.width = `${lineWidth}px`;
        line.style.height = `${fullHeight}px`;

        return {
          secondRatio: clamp01((secondCenterY - firstCenterY) / travel),
          thirdRatio: 1,
        };
      }

      const lineHeight = timelineThickness;
      const markerCenterY = firstRect.top + (firstRect.height / 2);

      if (isDesktopLayout()) {
        const viewportWidth = window.innerWidth || document.documentElement.clientWidth || wrapperRect.width;
        const secondCenterX = secondRect.left + (secondRect.width / 2);
        const lastCenterX = lastRect.left + (lastRect.width / 2);

        line.style.left = `${-wrapperRect.left}px`;
        line.style.top = `${markerCenterY - wrapperRect.top - (lineHeight / 2)}px`;
        line.style.width = `${viewportWidth}px`;
        line.style.height = `${lineHeight}px`;

        return {
          secondRatio: clamp01(secondCenterX / Math.max(1, viewportWidth)),
          thirdRatio: clamp01(lastCenterX / Math.max(1, viewportWidth)),
        };
      }

      const firstCenterX = firstRect.left + (firstRect.width / 2);
      const secondCenterX = secondRect.left + (secondRect.width / 2);
      const lastCenterX = lastRect.left + (lastRect.width / 2);
      const travel = Math.max(1, lastCenterX - firstCenterX);

      line.style.left = `${firstCenterX - wrapperRect.left}px`;
      line.style.top = `${markerCenterY - wrapperRect.top - (lineHeight / 2)}px`;
      line.style.width = `${travel}px`;
      line.style.height = `${lineHeight}px`;

      return {
        secondRatio: clamp01((secondCenterX - firstCenterX) / travel),
        thirdRatio: 1,
      };
    };

    const setLineVisible = (axis, progressValue = 1) => {
      line.style.opacity = '1';
      line.style.visibility = 'visible';
      progress.style.transformOrigin = axis === 'y' ? 'top center' : 'left center';
      progress.style.transform = axis === 'y'
        ? `scaleY(${progressValue})`
        : `scaleX(${progressValue})`;
    };

    const applyDesktopState = () => {
      setDesktopShell();

      const lineMetrics = getLineMetrics();
      const sectionRect = section.getBoundingClientRect();
      const viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
      const sectionProgress = clamp01((viewportHeight - sectionRect.top) / Math.max(sectionRect.height, 1));
      const revealWindow = 0.14;
      const secondReveal = clamp01((sectionProgress - lineMetrics.secondRatio) / revealWindow);
      const thirdStart = clamp01(Math.max(lineMetrics.secondRatio + revealWindow + 0.02, lineMetrics.thirdRatio - revealWindow));
      const thirdReveal = clamp01((sectionProgress - thirdStart) / revealWindow);

      setLineVisible('x', sectionProgress);
      setStepState(stepMeta[0], 1);
      setStepState(stepMeta[1], secondReveal);
      setStepState(stepMeta[2], thirdReveal);
    };

    const applyTabletState = () => {
      clearDesktopShell();
      getLineMetrics();
      setLineVisible('x', 1);
      setAllStepsVisible();
    };

    const applyCompactState = () => {
      clearDesktopShell();
      getLineMetrics();
      setLineVisible('y', 1);
      setAllStepsVisible();
    };

    const applyCurrentState = () => {
      if (isDesktopLayout()) {
        applyDesktopState();
        return;
      }

      if (isCompactLayout()) {
        applyCompactState();
        return;
      }

      applyTabletState();
    };

    const scheduleSync = () => {
      if (rafId) cancelAnimationFrame(rafId);
      rafId = requestAnimationFrame(() => {
        rafId = null;
        applyCurrentState();
      });
    };

    stepImages.filter((image) => !image.complete).forEach((image) => {
      image.addEventListener('load', scheduleSync);
      image.addEventListener('error', scheduleSync);
    });

    window.addEventListener('load', scheduleSync);
    window.addEventListener('resize', scheduleSync);
    window.addEventListener('scroll', scheduleSync, { passive: true });

    if ('ResizeObserver' in window) {
      resizeObserver = new ResizeObserver(scheduleSync);
      resizeObserver.observe(lineWrapper);
      resizeObserver.observe(stage);
    }

    applyCurrentState();
  };

  /* ─── App showcase zoom (eases from slightly small to full size on scroll) ── */
  const initShowcaseZoom = () => {
    const section = document.querySelector('.app-showcase');
    const container = section?.querySelector('.showcase-container');
    if (!section || !container) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const MIN = 0.9;   // starting scale as it enters from the bottom
    const EASE = 0.1;  // lower = smoother / longer glide
    let target = 1;
    let cur = 1;
    let rafId = null;

    const compute = () => {
      const rect = section.getBoundingClientRect();
      const vh = window.innerHeight;
      const start = vh;        // section top at viewport bottom → smallest
      const end = vh * 0.4;    // section top at 40% → full size
      const p = Math.max(0, Math.min(1, (start - rect.top) / (start - end)));
      target = MIN + p * (1 - MIN);
    };

    const apply = () => {
      container.style.transform = 'scale(' + cur.toFixed(4) + ')';
    };

    const animate = () => {
      cur += (target - cur) * EASE;
      apply();
      if (Math.abs(target - cur) > 0.001) {
        rafId = window.requestAnimationFrame(animate);
      } else {
        cur = target;
        apply();
        rafId = null;
      }
    };

    const onScroll = () => {
      compute();
      if (!rafId) rafId = window.requestAnimationFrame(animate);
    };

    compute();
    cur = target;
    apply();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
  };

  /* ─── Why Us cards intro (staggered pop-in when scrolled into view) ── */
  const initWhyusIntro = () => {
    const grid = document.querySelector('.whyus-grid');
    if (!grid) return;

    // Reduced motion or no observer support: just show the cards.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
        !('IntersectionObserver' in window)) {
      grid.classList.add('is-inview');
      return;
    }

    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        grid.classList.add('is-inview');
        obs.disconnect();
      });
    }, { threshold: 0.2 });

    io.observe(grid);
  };

  /* ─── Affordable cards intro (staggered rise into view) ── */
  const initAffordGridReveal = () => {
    const grid = document.querySelector('[data-afford-reveal]');
    if (!grid) return;

    const showGrid = () => {
      grid.classList.add('is-inview');
    };

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
        !('IntersectionObserver' in window)) {
      showGrid();
      return;
    }

    grid.classList.add('is-anim-ready');

    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        showGrid();
        obs.disconnect();
      });
    }, {
      rootMargin: '0px 0px -12% 0px',
      threshold: 0.2,
    });

    io.observe(grid);
  };

  /* ─── All Features gallery (sticky center + parallax sides + entry) ── */
  const initAllFeaturesGallery = () => {
    const section = document.querySelector('.allfeatures');
    const gallery = section?.querySelector('[data-af-gallery]');
    if (!section || !gallery) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animEls = Array.from(gallery.querySelectorAll('[data-af-anim]'));
    const feature = gallery.querySelector('[data-af-feature]');
    const parallaxEls = Array.from(gallery.querySelectorAll('[data-af-parallax]'));

    // 3. Entry fade-in — triggered once the section scrolls into view.
    if (reduceMotion) {
      animEls.forEach((el) => el.classList.add('is-visible'));
      feature?.classList.add('is-visible');
    } else {
      const io = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          animEls.forEach((el) => el.classList.add('is-visible'));
          feature?.classList.add('is-visible');
          obs.disconnect();
        });
      }, { threshold: 0 });
      io.observe(section);
    }

    // 2. Parallax — the whole gallery rises up toward the button as the section
    //    enters; side cards drift up, center card drifts slightly down. All eased
    //    so motion glides to a stop after scrolling.
    const centerEl = gallery.querySelector('[data-af-center]');
    if (!reduceMotion && (parallaxEls.length || centerEl)) {
      const SIDE_UP = 120;      // side cards travel upward (px)
      const CENTER_DOWN = 60;   // center card drifts down a little (px)
      const GALLERY_RISE = 150; // whole gallery rises up to meet the button (matches CSS margin-top)
      const HEAD_RISE = 70;     // header rises up from below as the section enters
      const EASE = 0.08;        // lower = smoother / longer glide to stop

      const headEl = section.querySelector('.allfeatures-head');

      let targetSide = 0;
      let targetCenter = 0;
      let targetGallery = 0;
      let targetHead = 0;
      let targetHeadOp = 1;
      let curSide = 0;
      let curCenter = 0;
      let curGallery = 0;
      let curHead = 0;
      let curHeadOp = 1;
      let rafId = null;

      const isDesktopAF = () => window.matchMedia('(min-width: 1025px)').matches;

      const computeTargets = () => {
        // Below desktop the gallery is a horizontal carousel — no vertical parallax.
        if (!isDesktopAF()) {
          targetSide = 0;
          targetCenter = 0;
          targetGallery = 0;
          targetHead = 0;
          targetHeadOp = 1;
          return;
        }
        const rect = section.getBoundingClientRect();
        const vh = window.innerHeight;
        const sectionHeight = section.offsetHeight;

        // Per-card drift: progress as the section scrolls through the viewport.
        const through = Math.max(0, Math.min(1,
          (-rect.top) / Math.max(sectionHeight - vh, 1)));
        targetSide = -through * SIDE_UP;
        targetCenter = through * CENTER_DOWN;

        // Gallery rise: as the section enters from the bottom, lift it up to the
        // button, then hold (clamped) once it has fully risen.
        const riseStart = vh * 0.9;
        const riseEnd = vh * 0.3;
        const rise = Math.max(0, Math.min(1, (riseStart - rect.top) / (riseStart - riseEnd)));
        targetGallery = -rise * GALLERY_RISE;

        // Header reveal: slides up from below + fades in as the section enters.
        const headStart = vh * 0.95;
        const headEnd = vh * 0.55;
        const headProgress = Math.max(0, Math.min(1, (headStart - rect.top) / (headStart - headEnd)));
        targetHead = (1 - headProgress) * HEAD_RISE;
        targetHeadOp = headProgress;
      };

      const apply = () => {
        const sy = curSide.toFixed(2);
        for (let i = 0; i < parallaxEls.length; i += 1) {
          parallaxEls[i].style.transform = 'translate3d(0,' + sy + 'px,0)';
        }
        if (centerEl) centerEl.style.transform = 'translate3d(0,' + curCenter.toFixed(2) + 'px,0)';
        gallery.style.transform = 'translate3d(0,' + curGallery.toFixed(2) + 'px,0)';
        if (headEl) {
          headEl.style.transform = 'translate3d(0,' + curHead.toFixed(2) + 'px,0)';
          headEl.style.opacity = curHeadOp.toFixed(3);
        }
      };

      const settled = () =>
        Math.abs(targetSide - curSide) < 0.1 &&
        Math.abs(targetCenter - curCenter) < 0.1 &&
        Math.abs(targetGallery - curGallery) < 0.1 &&
        Math.abs(targetHead - curHead) < 0.1 &&
        Math.abs(targetHeadOp - curHeadOp) < 0.01;

      const animate = () => {
        curSide += (targetSide - curSide) * EASE;
        curCenter += (targetCenter - curCenter) * EASE;
        curGallery += (targetGallery - curGallery) * EASE;
        curHead += (targetHead - curHead) * EASE;
        curHeadOp += (targetHeadOp - curHeadOp) * EASE;
        apply();
        if (!settled()) {
          rafId = window.requestAnimationFrame(animate);
        } else {
          curSide = targetSide;
          curCenter = targetCenter;
          curGallery = targetGallery;
          curHead = targetHead;
          curHeadOp = targetHeadOp;
          apply();
          rafId = null;
        }
      };

      const onScroll = () => {
        computeTargets();
        if (!rafId) rafId = window.requestAnimationFrame(animate);
      };

      // Snap to the initial position without animating in.
      computeTargets();
      curSide = targetSide;
      curCenter = targetCenter;
      curGallery = targetGallery;
      curHead = targetHead;
      curHeadOp = targetHeadOp;
      apply();

      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', onScroll, { passive: true });
    }

    // 4. Custom circular down-right arrow cursor when hovering the cards.
    //    Skipped for the plain (non-interactive) gallery — keep the normal cursor.
    if (!section.classList.contains('allfeatures--plain') && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      const cursor = document.createElement('div');
      cursor.className = 'carousel-cursor';
      cursor.setAttribute('aria-hidden', 'true');
      cursor.innerHTML =
        '<div class="carousel-cursor-inner">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" ' +
        'stroke-linecap="round" stroke-linejoin="round"><path d="M7 7l10 10M17 9v8h-8"/></svg>' +
        '</div>';
      document.body.appendChild(cursor);

      const moveCursor = (e) => {
        cursor.style.transform = 'translate(' + e.clientX + 'px,' + e.clientY + 'px)';
      };

      gallery.querySelectorAll('.af-card').forEach((card) => {
        card.addEventListener('mouseenter', (e) => {
          moveCursor(e);
          cursor.classList.add('is-visible');
        });
        card.addEventListener('mouseleave', () => {
          cursor.classList.remove('is-visible', 'is-down');
        });
        card.addEventListener('mousemove', moveCursor);
        card.addEventListener('mousedown', () => cursor.classList.add('is-down'));
      });
      window.addEventListener('mouseup', () => cursor.classList.remove('is-down'));
    }
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

    const updatePreviewAspect = () => {
      const src = preview.currentSrc || preview.getAttribute('src') || '';
      const isPortraitMedia =
        !isSubscriptionSrc(src) &&
        preview.naturalWidth > 0 &&
        preview.naturalHeight > preview.naturalWidth;

      card?.classList.toggle('is-portrait-preview', isPortraitMedia);
      wrap?.classList.toggle('is-portrait-preview', isPortraitMedia);
    };

    const updatePreviewMode = (src) => {
      const isSubscription = isSubscriptionSrc(src);
      card?.classList.toggle('is-subscription-preview', isSubscription);
      card?.classList.toggle('is-media-preview', !isSubscription);
      card?.classList.toggle('is-portrait-preview', false);
      wrap?.classList.toggle('is-subscription-preview', isSubscription);
      wrap?.classList.toggle('is-media-preview', !isSubscription);
      wrap?.classList.toggle('is-portrait-preview', false);
      if (!isSubscription) {
        preview.style.transform = 'none';
      }
    };

    preview.addEventListener('load', updatePreviewAspect);

    updatePreviewMode(preview.getAttribute('src') || '');
    updatePreviewAspect();

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
      shine.style.transform = 'none';
      shine.style.backgroundImage = 'none';
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
      /* Keep the shine locked to the tilting image so it doesn't leave a static
         light patch ("grey mark") at the image's original position. */
      shine.style.transform = img.style.transform;

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
    const articleChunks = articleSections.length > 0
      ? articleSections.flatMap((section) => {
        const heading = normalizeNarrationText(section.querySelector('h2')?.textContent);
        const paragraphs = Array.from(section.querySelectorAll('p'))
          .map((paragraph) => normalizeNarrationText(paragraph.textContent))
          .filter(Boolean)
          .join(' ');

        const sectionText = [heading ? `${heading}.` : '', paragraphs].filter(Boolean).join(' ');
        return splitNarrationChunks(sectionText, 420);
      })
      : splitNarrationChunks(normalizeNarrationText(articleBody?.innerText), 420);

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

  /* ─── Hero banner zoom (pricing/why/retailer hero banners) ── */
  const initHeroBannerZoom = () => {
    const banners = Array.from(document.querySelectorAll('.pricing-hero-banner'));
    if (!banners.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    banners.forEach((banner) => {
      const hero = banner.closest('.pricing-hero, .why-hero, .retailer-hero') || banner.parentElement;
      banner.style.transformOrigin = 'center top';
      banner.style.willChange = 'transform';
      let ticking = false;
      const update = () => {
        const h = hero.offsetHeight || 1;
        const progress = Math.min(Math.max(window.scrollY / h, 0), 1);
        banner.style.transform = 'scale(' + (1 - progress * 0.12).toFixed(4) + ')';
        ticking = false;
      };
      const onScroll = () => { if (!ticking) { window.requestAnimationFrame(update); ticking = true; } };
      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', update);
      update();
    });
  };

  /* ─── Boot ────────────────────────────────────────────── */
  onReady(() => {
    initHeroBannerZoom();
    initFaqAccordion();
    initFaqPageNavigation();
    initDragScroll();
    initRetailerCarouselSlider();
    initMobileNav();
    initNewsArticleActions();
    initPricingCardTilt();
    initPricingThumbnails();
    initWhyusIntro();
    initAffordGridReveal();
    initShowcaseZoom();
    initAllFeaturesGallery();

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

/* ── Dark-mode ambient glow ──────────────────────────────────────────
   The light part of the dark background drifts a hair to the right and
   brightens as you scroll — barely noticeable, but it keeps the page
   feeling alive. Dark mode only; skipped for reduced-motion users. */
(function () {
  var root = document.documentElement;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  var ticking = false;
  function update() {
    ticking = false;
    if (root.getAttribute('data-theme') !== 'dark') return;
    var max = (document.documentElement.scrollHeight - window.innerHeight) || 1;
    var p = Math.min(1, Math.max(0, window.scrollY / max));   // 0 -> 1 scroll progress
    root.style.setProperty('--dm-glow-x', (82 + p * 8).toFixed(2) + '%');   // 82% -> 90%
    root.style.setProperty('--dm-glow-a', (0.14 + p * 0.07).toFixed(3));    // 0.14 -> 0.21
  }
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
  }, { passive: true });
  update();
})();
