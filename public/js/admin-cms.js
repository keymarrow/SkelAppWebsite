(function () {
  const SIDEBAR_STORAGE_KEY = 'skelapp-admin-sidebar-collapsed';

  initAdminSidebarToggle();

  document.querySelectorAll('[data-cms-repeater]').forEach((repeater) => {
    const rowsContainer = repeater.querySelector('[data-repeater-rows]');
    const addBtn = repeater.querySelector('[data-repeater-add]');
    const name = repeater.dataset.repeaterName || '';

    if (!rowsContainer || !addBtn) return;

    const reindex = () => {
      // For every row, rewrite name="content[<name>.<INDEX>.<field>]"
      const rows = rowsContainer.querySelectorAll('[data-repeater-row]');
      rows.forEach((row, idx) => {
        row.querySelectorAll('[name]').forEach((el) => {
          const nameAttr = el.getAttribute('name');
          // Match content[<name>.<digits>.<rest>]  OR image[<name>.<digits>.<rest>]
          const re = new RegExp(`^(content|image)\\[${escapeRegex(name)}\\.\\d+\\.(.+)\\]$`);
          const match = nameAttr.match(re);
          if (match) {
            el.setAttribute('name', `${match[1]}[${name}.${idx}.${match[2]}]`);
          }
        });
        row.querySelectorAll('[id]').forEach((el) => {
          const idAttr = el.getAttribute('id');
          const re = new RegExp(`^field-${escapeRegex(name)}-\\d+-(.+)$`);
          const match = idAttr.match(re);
          if (match) {
            el.setAttribute('id', `field-${name}-${idx}-${match[1]}`);
          }
        });
        row.querySelectorAll('label[for]').forEach((label) => {
          const forAttr = label.getAttribute('for');
          const re = new RegExp(`^field-${escapeRegex(name)}-\\d+-(.+)$`);
          const match = forAttr.match(re);
          if (match) {
            label.setAttribute('for', `field-${name}-${idx}-${match[1]}`);
          }
        });
      });
    };

    addBtn.addEventListener('click', () => {
      const rows = rowsContainer.querySelectorAll('[data-repeater-row]');
      const lastRow = rows[rows.length - 1];
      const clone = (lastRow || rowsContainer.querySelector('[data-repeater-row]')).cloneNode(true);
      // Clear values inside the clone
      clone.querySelectorAll('input[type="text"], textarea').forEach((el) => { el.value = ''; });
      clone.querySelectorAll('input[type="checkbox"]').forEach((el) => { el.checked = false; });
      // Reset image fields in the clone (URL, thumb, remove button, wiring flag)
      clone.querySelectorAll('[data-cms-image-field]').forEach((field) => {
        delete field.dataset.cmsImageWired;
        const urlInput = field.querySelector('[data-cms-image-url]');
        if (urlInput) urlInput.value = '';
        const thumb = field.querySelector('[data-cms-image-thumb]');
        if (thumb) {
          thumb.classList.add('cms-image-thumb--empty');
          thumb.innerHTML = '<span data-cms-image-preview-empty>No image</span>';
        }
        const removeBtn = field.querySelector('[data-cms-image-remove]');
        if (removeBtn) removeBtn.hidden = true;
        const feedback = field.querySelector('[data-cms-image-feedback]');
        if (feedback) { feedback.hidden = true; feedback.textContent = ''; }
        const fileInput = field.querySelector('[data-cms-image-file-input]');
        if (fileInput) fileInput.value = '';
      });
      rowsContainer.appendChild(clone);
      reindex();
      bindRemove(clone);
      // Re-wire any image fields in the new row.
      if (typeof window.__cmsInitImageField === 'function') {
        clone.querySelectorAll('[data-cms-image-field]').forEach((field) => {
          window.__cmsInitImageField(field);
        });
      }
      repeater.dispatchEvent(new CustomEvent('cms:preview-sync-needed', { bubbles: true }));
    });

    const bindRemove = (row) => {
      const btn = row.querySelector('[data-repeater-remove]');
      if (!btn) return;
      btn.addEventListener('click', () => {
        const remaining = rowsContainer.querySelectorAll('[data-repeater-row]').length;
        if (remaining <= 1) {
          // keep one row visible — just clear it
          row.querySelectorAll('input[type="text"], textarea').forEach((el) => { el.value = ''; });
          row.querySelectorAll('input[type="checkbox"]').forEach((el) => { el.checked = false; });
          repeater.dispatchEvent(new CustomEvent('cms:preview-sync-needed', { bubbles: true }));
          return;
        }
        row.remove();
        reindex();
        repeater.dispatchEvent(new CustomEvent('cms:preview-sync-needed', { bubbles: true }));
      });
    };

    rowsContainer.querySelectorAll('[data-repeater-row]').forEach(bindRemove);
  });

  document.querySelectorAll('[data-cms-editor]').forEach((editor) => {
    const form = editor.querySelector('[data-cms-form]');
    const frame = editor.querySelector('[data-cms-preview-frame]');

    if (!form || !frame) return;

    const syncUrl = editor.dataset.previewSyncUrl || '';
    const baseUrl = editor.dataset.previewBaseUrl || frame.getAttribute('src') || '';
    const defaultTarget = editor.dataset.previewDefaultTarget || '';
    const targetSelect = editor.querySelector('[data-cms-preview-target]');
    const refreshBtn = editor.querySelector('[data-cms-preview-refresh]');
    const openLink = editor.querySelector('[data-cms-preview-open]');
    const statusEl = editor.querySelector('[data-cms-preview-status]');
    const csrfToken = form.querySelector('input[name="_token"]')?.value || '';
    const contentFieldSelector = '[name^="content["], [name="remove_image[]"]';

    let syncTimer = null;
    let requestCounter = 0;
    let previewAbortController = null;
    let lastTypingAt = 0;
    const TYPING_IDLE_DELAY = 2000;
    let nativeSubmitPrepared = false;

    const setStatus = (message, state) => {
      if (!statusEl) return;
      statusEl.textContent = message;
      statusEl.dataset.state = state || 'idle';
    };

    const previewTarget = () => targetSelect?.value || defaultTarget;

    const buildPreviewUrl = (cacheBust) => {
      const url = new URL(baseUrl, window.location.origin);
      const target = previewTarget();

      if (target) {
        url.searchParams.set('target', target);
      }

      if (cacheBust) {
        url.searchParams.set('ts', String(Date.now()));
      }

      return url.toString();
    };

    const syncOpenLink = () => {
      if (openLink) {
        openLink.href = buildPreviewUrl(false);
      }
    };

    const reloadPreview = () => {
      frame.src = buildPreviewUrl(true);
      syncOpenLink();
    };

    const collectContentPayload = () => {
      const payload = {};
      const removeImage = [];
      const sourceData = new FormData(form);

      sourceData.forEach((value, name) => {
        if (typeof value !== 'string') {
          return;
        }

        const contentMatch = name.match(/^content\[(.+)\]$/);
        if (contentMatch) {
          payload[contentMatch[1]] = value;
          return;
        }

        if (name === 'remove_image[]' && value !== '') {
          removeImage.push(value);
        }
      });

      return { payload, removeImage };
    };

    const ensureSerializedField = (key, attr) => {
      let input = form.querySelector(`input[data-${attr}]`);
      if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.setAttribute(`data-${attr}`, '1');
        form.appendChild(input);
      }

      return input;
    };

    const syncSerializedFields = () => {
      const { payload, removeImage } = collectContentPayload();
      ensureSerializedField('content_payload', 'cms-content-payload').value = JSON.stringify(payload);
      ensureSerializedField('remove_image_payload', 'cms-remove-image-payload').value = JSON.stringify(removeImage);

      return { payload, removeImage };
    };

    const disableExpandedContentFields = () => {
      form.querySelectorAll(contentFieldSelector).forEach((field) => {
        field.disabled = true;
      });
    };

    const mirrorSubmitter = (submitter) => {
      if (!submitter?.name) {
        return;
      }

      let mirror = form.querySelector('input[data-cms-submitter-mirror]');
      if (!mirror) {
        mirror = document.createElement('input');
        mirror.type = 'hidden';
        mirror.setAttribute('data-cms-submitter-mirror', '1');
        form.appendChild(mirror);
      }

      mirror.name = submitter.name;
      mirror.value = submitter.value;
    };

    const isTextEntryTarget = (target) => {
      return target instanceof HTMLTextAreaElement
        || (target instanceof HTMLInputElement && !['file', 'checkbox', 'radio', 'range', 'color', 'date', 'datetime-local', 'month', 'time', 'week'].includes(target.type || 'text'));
    };

    const syncPreview = async ({ force = false } = {}) => {
      if (!syncUrl) return;

      if (!force) {
        const typingAge = Date.now() - lastTypingAt;
        if (typingAge < TYPING_IDLE_DELAY) {
          queueSync(TYPING_IDLE_DELAY - typingAge);
          return;
        }
      }

      requestCounter += 1;
      const requestId = requestCounter;
      const { payload, removeImage } = syncSerializedFields();
      const formData = new FormData();
      formData.append('_token', csrfToken);
      formData.append('content_payload', JSON.stringify(payload));
      formData.append('remove_image_payload', JSON.stringify(removeImage));

      if (previewAbortController) {
        previewAbortController.abort();
      }
      previewAbortController = new AbortController();

      setStatus('Updating preview…', 'loading');

      try {
        const response = await fetch(syncUrl, {
          method: 'POST',
          body: formData,
          signal: previewAbortController.signal,
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        if (!response.ok) {
          throw new Error(`Preview sync failed with status ${response.status}`);
        }

        if (requestId !== requestCounter) {
          return;
        }

        reloadPreview();
      } catch (error) {
        if (error?.name === 'AbortError') {
          return;
        }

        if (requestId !== requestCounter) {
          return;
        }

        console.error(error);
        setStatus('Preview update failed. Save draft or refresh to try again.', 'error');
      }
    };

    const queueSync = (delay = 450, { force = false } = {}) => {
      window.clearTimeout(syncTimer);
      syncTimer = window.setTimeout(() => syncPreview({ force }), delay);
    };

    form.addEventListener('input', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.type === 'file') {
        return;
      }

      if (isTextEntryTarget(event.target)) {
        lastTypingAt = Date.now();
        setStatus('Typing… preview updates when you pause.', 'idle');
        queueSync(TYPING_IDLE_DELAY);
        return;
      }

      queueSync(220);
    });

    form.addEventListener('change', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.type === 'file') {
        setStatus('Save draft to preview new image uploads.', 'idle');
        return;
      }

      queueSync(0, { force: true });
    });

    form.addEventListener('focusout', (event) => {
      if (!isTextEntryTarget(event.target)) {
        return;
      }

      queueSync(0, { force: true });
    });

    form.addEventListener('cms:preview-sync-needed', () => {
      queueSync(80, { force: true });
    });

    targetSelect?.addEventListener('change', () => {
      syncOpenLink();
      queueSync(0, { force: true });
    });

    refreshBtn?.addEventListener('click', () => {
      window.clearTimeout(syncTimer);
      syncPreview({ force: true });
    });

    form.addEventListener('submit', (event) => {
      if (nativeSubmitPrepared) {
        return;
      }

      nativeSubmitPrepared = true;
      event.preventDefault();

      syncSerializedFields();
      disableExpandedContentFields();
      mirrorSubmitter(event.submitter || null);

      if (event.submitter?.formAction) {
        form.action = event.submitter.formAction;
      }

      if (event.submitter?.formMethod) {
        form.method = event.submitter.formMethod;
      }

      form.submit();
    });

    frame.addEventListener('load', () => {
      if ((statusEl?.dataset.state || '') !== 'error') {
        setStatus('Preview updated.', 'success');
      }
    });

    // Viewport-mode buttons (Desktop / Tablet / Mobile).
    // The iframe is resized to the *true* device width (e.g. 390px for mobile)
    // so the public CSS picks the matching @media breakpoint. We then visually
    // shrink it with a CSS transform so it fits the preview panel.
    const PREVIEW_MODE_KEY = 'skelapp-admin-preview-mode';
    const PREVIEW_DEVICE_DIMENSIONS = {
      desktop: { width: 1440, height: 950 },
      tablet: { width: 768, height: 1024 },
      mobile: { width: 390, height: 844 },
    };
    const modeButtons = editor.querySelectorAll('[data-cms-preview-mode]');
    const frameWrap = editor.querySelector('[data-cms-preview-frame-wrap]');
    const frameStage = editor.querySelector('[data-cms-preview-frame-stage]');
    let currentMode = frameWrap?.dataset.mode || 'desktop';

    const recomputeScale = () => {
      if (!frameWrap || !frameStage) return;
      const dims = PREVIEW_DEVICE_DIMENSIONS[currentMode] || PREVIEW_DEVICE_DIMENSIONS.desktop;
      const styles = getComputedStyle(frameWrap);
      const padX = parseFloat(styles.paddingLeft) + parseFloat(styles.paddingRight);
      const padY = parseFloat(styles.paddingTop) + parseFloat(styles.paddingBottom);
      const innerWidth = frameWrap.clientWidth - padX;
      if (innerWidth <= 0) return;
      const scale = Math.min(1, innerWidth / dims.width);
      // Set the stage to the exact device pixel size — belt-and-suspenders, so
      // the iframe definitely loads with that viewport regardless of CSS var
      // resolution timing.
      frameStage.style.width = dims.width + 'px';
      frameStage.style.height = dims.height + 'px';
      frameStage.style.setProperty('--scale', scale);
      // And mirror onto the iframe in case width:100% doesn't propagate in
      // some browsers during transitions.
      if (frame) {
        frame.style.width = dims.width + 'px';
        frame.style.height = dims.height + 'px';
      }
      // Resize the wrap to hug the scaled iframe height (no empty gutter).
      const scaledHeight = dims.height * scale;
      const maxHeight = window.innerHeight * 0.82;
      frameWrap.style.height = Math.min(scaledHeight, maxHeight) + padY + 'px';
    };

    const applyMode = (mode, { force = false } = {}) => {
      if (!frameWrap) return;
      const prevMode = currentMode;
      currentMode = mode;
      frameWrap.dataset.mode = mode;
      modeButtons.forEach((btn) => {
        const isActive = btn.dataset.cmsPreviewMode === mode;
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });
      try { localStorage.setItem(PREVIEW_MODE_KEY, mode); } catch (err) { /* ignore */ }

      window.requestAnimationFrame(() => {
        recomputeScale();
        // Force a fresh load of the public page at the new viewport size so
        // CSS media queries are re-evaluated by the iframe's content document.
        if (frame && (force || prevMode !== mode)) {
          const url = new URL(frame.src, window.location.origin);
          url.searchParams.set('mode', mode);
          url.searchParams.set('ts', String(Date.now()));
          frame.src = url.toString();
          syncOpenLink();
        }
      });
    };

    modeButtons.forEach((btn) => {
      btn.addEventListener('click', () => applyMode(btn.dataset.cmsPreviewMode));
    });

    // Restore last-used mode (falls back to desktop).
    let restored = false;
    try {
      const saved = localStorage.getItem(PREVIEW_MODE_KEY);
      if (saved && saved in PREVIEW_DEVICE_DIMENSIONS) {
        applyMode(saved, { force: true });
        restored = true;
      }
    } catch (err) { /* ignore */ }
    if (!restored) {
      // Apply default desktop dims explicitly so initial measure is correct.
      applyMode(currentMode, { force: false });
    }

    // Recompute when the panel resizes (sidebar collapse, window resize).
    if (typeof ResizeObserver === 'function' && frameWrap) {
      new ResizeObserver(() => recomputeScale()).observe(frameWrap);
    } else {
      window.addEventListener('resize', recomputeScale);
    }

    syncOpenLink();
    queueSync(20);
  });

  document.querySelectorAll('[data-news-preview-editor]').forEach((editor) => {
    const form = editor.querySelector('[data-news-preview-form]');
    const frame = editor.querySelector('[data-news-preview-frame]');

    if (!form || !frame) return;

    const syncUrl = editor.dataset.previewSyncUrl || '';
    const baseUrl = editor.dataset.previewBaseUrl || frame.getAttribute('src') || '';
    const refreshBtn = editor.querySelector('[data-news-preview-refresh]');
    const openLink = editor.querySelector('[data-news-preview-open]');
    const statusEl = editor.querySelector('[data-news-preview-status]');
    const csrfToken = form.querySelector('input[name="_token"]')?.value || '';

    let syncTimer = null;
    let requestCounter = 0;
    let previewAbortController = null;
    let lastTypingAt = 0;
    const TYPING_IDLE_DELAY = 2000;

    const setStatus = (message, state) => {
      if (!statusEl) return;
      statusEl.textContent = message;
      statusEl.dataset.state = state || 'idle';
    };

    const buildPreviewUrl = (cacheBust) => {
      const url = new URL(baseUrl, window.location.origin);

      if (cacheBust) {
        url.searchParams.set('ts', String(Date.now()));
      }

      return url.toString();
    };

    const syncOpenLink = () => {
      if (openLink) {
        openLink.href = buildPreviewUrl(false);
      }
    };

    const reloadPreview = () => {
      frame.src = buildPreviewUrl(true);
      syncOpenLink();
    };

    const isTextEntryTarget = (target) => {
      return target instanceof HTMLTextAreaElement
        || (target instanceof HTMLInputElement && !['file', 'checkbox', 'radio', 'range', 'color', 'date', 'datetime-local', 'month', 'time', 'week'].includes(target.type || 'text'));
    };

    const syncPreview = async ({ force = false } = {}) => {
      if (!syncUrl) return;

      if (!force) {
        const typingAge = Date.now() - lastTypingAt;
        if (typingAge < TYPING_IDLE_DELAY) {
          queueSync(TYPING_IDLE_DELAY - typingAge);
          return;
        }
      }

      requestCounter += 1;
      const requestId = requestCounter;
      const formData = new FormData(form);

      form.querySelectorAll('input[type="file"][name]').forEach((input) => {
        formData.delete(input.name);
      });

      if (previewAbortController) {
        previewAbortController.abort();
      }
      previewAbortController = new AbortController();

      setStatus('Updating preview…', 'loading');

      try {
        const response = await fetch(syncUrl, {
          method: 'POST',
          body: formData,
          signal: previewAbortController.signal,
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        if (!response.ok) {
          throw new Error(`Preview sync failed with status ${response.status}`);
        }

        if (requestId !== requestCounter) {
          return;
        }

        reloadPreview();
      } catch (error) {
        if (error?.name === 'AbortError') {
          return;
        }

        if (requestId !== requestCounter) {
          return;
        }

        console.error(error);
        setStatus('Preview update failed. Save the post or refresh to try again.', 'error');
      }
    };

    const queueSync = (delay = 450, { force = false } = {}) => {
      window.clearTimeout(syncTimer);
      syncTimer = window.setTimeout(() => syncPreview({ force }), delay);
    };

    form.addEventListener('input', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.type === 'file') {
        return;
      }

      if (isTextEntryTarget(event.target)) {
        lastTypingAt = Date.now();
        setStatus('Typing… preview updates when you pause.', 'idle');
        queueSync(TYPING_IDLE_DELAY);
        return;
      }

      queueSync(220);
    });

    form.addEventListener('change', (event) => {
      if (event.target instanceof HTMLInputElement && event.target.type === 'file') {
        setStatus('Save the post to preview uploaded images.', 'idle');
        return;
      }

      queueSync(0, { force: true });
    });

    form.addEventListener('focusout', (event) => {
      if (!isTextEntryTarget(event.target)) {
        return;
      }

      queueSync(0, { force: true });
    });

    form.addEventListener('news:preview-sync-needed', () => {
      queueSync(80, { force: true });
    });

    refreshBtn?.addEventListener('click', () => {
      window.clearTimeout(syncTimer);
      syncPreview({ force: true });
    });

    frame.addEventListener('load', () => {
      if ((statusEl?.dataset.state || '') !== 'error') {
        setStatus('Preview updated.', 'success');
      }
    });

    syncOpenLink();
  });

  // Exposed so cloned repeater rows can call this for new image fields.
  window.__cmsInitImageField = null;

  initCmsImagePickers();
  initCmsTypographyTabs();

  function initCmsTypographyTabs() {
    document.querySelectorAll('[data-cms-typography-field]').forEach((field) => {
      const tabs = field.querySelectorAll('[data-typography-tab]');
      const bodies = field.querySelectorAll('[data-typography-body]');
      if (!tabs.length || !bodies.length) return;

      tabs.forEach((tab) => {
        tab.addEventListener('click', (event) => {
          event.preventDefault();
          const target = tab.dataset.typographyTab;
          tabs.forEach((t) => {
            const isActive = t === tab;
            t.classList.toggle('is-active', isActive);
            t.setAttribute('aria-selected', isActive ? 'true' : 'false');
          });
          bodies.forEach((body) => {
            const isMatch = body.dataset.typographyBody === target;
            body.classList.toggle('is-active', isMatch);
            body.hidden = !isMatch;
          });
        });
      });
    });
  }

  function initCmsImagePickers() {
    const modal = document.querySelector('[data-cms-media-modal]');
    const csrfInput = document.querySelector('[data-cms-form] input[name="_token"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    const fields = document.querySelectorAll('[data-cms-image-field]');
    if (fields.length === 0 && !modal) return;

    // ── Modal state ─────────────────────────────────────────────────
    let mediaImages = [];
    let mediaLoaded = false;
    let mediaSelected = null;
    let activeField = null;

    const grid = modal?.querySelector('[data-cms-media-grid]');
    const emptyEl = modal?.querySelector('[data-cms-media-empty]');
    const feedbackEl = modal?.querySelector('[data-cms-media-feedback]');
    const searchInput = modal?.querySelector('[data-cms-media-search]');
    const chooseBtn = modal?.querySelector('[data-cms-media-choose]');
    const uploadInputModal = modal?.querySelector('[data-cms-media-upload-input]');
    const libraryUrl = modal?.dataset.libraryUrl || '';
    const uploadUrl = modal?.dataset.uploadUrl || '';

    const setFeedback = (message, isError) => {
      if (!feedbackEl) return;
      if (!message) {
        feedbackEl.hidden = true;
        feedbackEl.textContent = '';
        return;
      }
      feedbackEl.hidden = false;
      feedbackEl.textContent = message;
      feedbackEl.dataset.state = isError ? 'error' : 'info';
    };

    const renderGrid = () => {
      if (!grid || !emptyEl || !chooseBtn) return;

      const q = (searchInput?.value || '').trim().toLowerCase();
      const filtered = mediaImages.filter((img) => {
        return img.name.toLowerCase().includes(q) || (img.alt || '').toLowerCase().includes(q);
      });

      grid.innerHTML = '';

      if (filtered.length === 0) {
        emptyEl.hidden = false;
        chooseBtn.disabled = true;
        return;
      }
      emptyEl.hidden = true;

      filtered.forEach((image) => {
        const card = document.createElement('button');
        card.type = 'button';
        card.className = 'cms-media-card';
        card.dataset.selected = mediaSelected?.url === image.url ? 'true' : 'false';

        const preview = document.createElement('div');
        preview.className = 'cms-media-card-preview';
        const img = document.createElement('img');
        img.src = image.url;
        img.alt = image.alt || '';
        preview.appendChild(img);

        const meta = document.createElement('div');
        meta.className = 'cms-media-card-meta';
        const title = document.createElement('strong');
        title.textContent = image.name;
        const section = document.createElement('span');
        section.textContent = image.section === 'covers'
          ? 'News cover'
          : image.section === 'cms'
            ? 'CMS upload'
            : 'News body';
        meta.append(title, section);

        card.append(preview, meta);

        card.addEventListener('click', () => {
          mediaSelected = image;
          chooseBtn.disabled = false;
          renderGrid();
        });

        grid.appendChild(card);
      });

      if (mediaSelected && !filtered.some((img) => img.url === mediaSelected.url)) {
        mediaSelected = null;
        chooseBtn.disabled = true;
      }
    };

    const loadLibrary = async (force = false) => {
      if (!grid || !libraryUrl) return;
      if (mediaLoaded && !force) {
        renderGrid();
        return;
      }
      setFeedback('Loading images…', false);
      try {
        const response = await fetch(libraryUrl, {
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin',
        });
        const payload = await response.json();
        if (!response.ok || !Array.isArray(payload.images)) {
          throw new Error(payload.message || 'Failed to load images.');
        }
        mediaImages = payload.images;
        mediaLoaded = true;
        setFeedback('', false);
        renderGrid();
      } catch (err) {
        console.error(err);
        setFeedback('Could not load the image library. Try again.', true);
      }
    };

    const openModal = (field) => {
      if (!modal) return;
      activeField = field;
      mediaSelected = null;
      if (chooseBtn) chooseBtn.disabled = true;
      if (searchInput) searchInput.value = '';
      modal.hidden = false;
      document.body.classList.add('cms-modal-open');
      loadLibrary(false);
    };

    const closeModal = () => {
      if (!modal) return;
      modal.hidden = true;
      activeField = null;
      document.body.classList.remove('cms-modal-open');
    };

    const applyImageToField = (field, url) => {
      if (!field) return;
      const urlInput = field.querySelector('[data-cms-image-url]');
      const thumb = field.querySelector('[data-cms-image-thumb]');
      const removeBtn = field.querySelector('[data-cms-image-remove]');

      if (urlInput) urlInput.value = url;
      if (thumb) {
        thumb.classList.toggle('cms-image-thumb--empty', !url);
        thumb.innerHTML = url
          ? `<img src="${escapeHtml(url)}" alt="" data-cms-image-preview>`
          : '<span data-cms-image-preview-empty>No image</span>';
      }
      if (removeBtn) removeBtn.hidden = !url;

      // Tell the live preview to refresh.
      field.dispatchEvent(new CustomEvent('cms:preview-sync-needed', { bubbles: true }));
    };

    const uploadFile = async (file) => {
      if (!uploadUrl || !file) return null;
      const formData = new FormData();
      formData.append('image', file);
      const response = await fetch(uploadUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
      });
      if (!response.ok) {
        const text = await response.text().catch(() => '');
        throw new Error(text || `Upload failed (${response.status})`);
      }
      return response.json();
    };

    const wireField = (field) => {
      if (!field || field.dataset.cmsImageWired === '1') return;
      field.dataset.cmsImageWired = '1';

      const uploadBtn = field.querySelector('[data-cms-image-upload]');
      const browseBtn = field.querySelector('[data-cms-image-browse]');
      const removeBtn = field.querySelector('[data-cms-image-remove]');
      const fileInput = field.querySelector('[data-cms-image-file-input]');
      const feedback = field.querySelector('[data-cms-image-feedback]');

      const showFeedback = (message, isError) => {
        if (!feedback) return;
        if (!message) {
          feedback.hidden = true;
          feedback.textContent = '';
          return;
        }
        feedback.hidden = false;
        feedback.textContent = message;
        feedback.dataset.state = isError ? 'error' : 'info';
      };

      uploadBtn?.addEventListener('click', () => fileInput?.click());

      fileInput?.addEventListener('change', async () => {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;
        showFeedback('Uploading…', false);
        try {
          const payload = await uploadFile(file);
          if (payload && payload.url) {
            applyImageToField(field, payload.url);
            showFeedback('Uploaded.', false);
            mediaLoaded = false;
            window.setTimeout(() => showFeedback('', false), 2000);
          } else {
            showFeedback('Upload returned no URL.', true);
          }
        } catch (err) {
          console.error(err);
          showFeedback('Upload failed. Try a smaller file or different format.', true);
        } finally {
          fileInput.value = '';
        }
      });

      browseBtn?.addEventListener('click', () => openModal(field));

      removeBtn?.addEventListener('click', () => {
        applyImageToField(field, '');
      });
    };

    window.__cmsInitImageField = wireField;
    fields.forEach(wireField);

    // Modal controls
    modal?.querySelectorAll('[data-cms-media-close]').forEach((el) => {
      el.addEventListener('click', closeModal);
    });

    searchInput?.addEventListener('input', renderGrid);

    chooseBtn?.addEventListener('click', () => {
      if (!mediaSelected || !activeField) return;
      applyImageToField(activeField, mediaSelected.url);
      closeModal();
    });

    uploadInputModal?.addEventListener('change', async () => {
      const file = uploadInputModal.files && uploadInputModal.files[0];
      if (!file) return;
      setFeedback('Uploading…', false);
      try {
        const payload = await uploadFile(file);
        if (payload && payload.url) {
          mediaLoaded = false;
          await loadLibrary(true);
          // Pre-select the just-uploaded image so user can confirm with one click.
          mediaSelected = mediaImages.find((img) => img.url === payload.url) || null;
          if (chooseBtn) chooseBtn.disabled = !mediaSelected;
          renderGrid();
          setFeedback('Uploaded — click "Choose selected" to use it.', false);
        }
      } catch (err) {
        console.error(err);
        setFeedback('Upload failed. Try a smaller file or different format.', true);
      } finally {
        uploadInputModal.value = '';
      }
    });

    // Close on Escape
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && modal && !modal.hidden) closeModal();
    });
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, (c) => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
    }[c]));
  }

  function escapeRegex(s) {
    return String(s).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  function initAdminSidebarToggle() {
    const body = document.body;
    const toggle = document.querySelector('[data-admin-sidebar-toggle]');

    if (!body || !toggle) return;

    const mediaQuery = window.matchMedia('(min-width: 1081px)');

    const readPreference = () => {
      try {
        return window.localStorage.getItem(SIDEBAR_STORAGE_KEY) === 'true';
      } catch (error) {
        return false;
      }
    };

    const writePreference = (collapsed) => {
      try {
        window.localStorage.setItem(SIDEBAR_STORAGE_KEY, collapsed ? 'true' : 'false');
      } catch (error) {
        // Ignore storage errors and continue with the in-memory state.
      }
    };

    const applyState = (collapsed) => {
      body.classList.toggle('admin-body--sidebar-collapsed', collapsed);
      toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
      toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
      toggle.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    };

    const syncFromStorage = () => {
      const shouldCollapse = mediaQuery.matches && readPreference();
      applyState(shouldCollapse);
    };

    toggle.addEventListener('click', () => {
      if (!mediaQuery.matches) return;

      const collapsed = !body.classList.contains('admin-body--sidebar-collapsed');
      writePreference(collapsed);
      applyState(collapsed);
    });

    if (typeof mediaQuery.addEventListener === 'function') {
      mediaQuery.addEventListener('change', syncFromStorage);
    } else if (typeof mediaQuery.addListener === 'function') {
      mediaQuery.addListener(syncFromStorage);
    }

    syncFromStorage();
  }
})();
