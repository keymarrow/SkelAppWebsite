@php
  $isEditing = $post->exists;
  $publicUrl = $post->exists ? route('news.show', $post->slug) : null;
  $currentCardImage = old('featured_image_existing', $post->featured_image_url);
@endphp

<div class="admin-form-grid">
  <section class="admin-panel">
    <div class="admin-panel-heading">
      <h2>Content</h2>
    </div>

    <div class="admin-form-fields">
      <label>
        <span>Title</span>
        <input type="text" name="title" value="{{ old('title', $post->title) }}" maxlength="255" required data-slug-source>
        @error('title')<small>{{ $message }}</small>@enderror
      </label>

      <label>
        <span>Slug</span>
        <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" maxlength="255" required data-slug-target>
        @error('slug')<small>{{ $message }}</small>@enderror
      </label>

      <label>
        <span>Summary</span>
        <textarea name="summary" rows="4" maxlength="500" required>{{ old('summary', $post->summary) }}</textarea>
        @error('summary')<small>{{ $message }}</small>@enderror
      </label>

      <div class="admin-upload-stack">
        <section class="admin-upload-card">
          <div class="admin-upload-card-heading">
            <span class="admin-field-label">Card image</span>
            <small>Optional. Used on news cards only. Add article images from the body toolbar.</small>
          </div>

          <figure class="admin-image-preview" data-cover-preview @if (!$currentCardImage) hidden @endif>
            <img
              src="{{ $currentCardImage ?? '' }}"
              alt="{{ $post->title ?: 'Card image preview' }}"
              data-cover-preview-image
              data-initial-src="{{ $currentCardImage ?? '' }}"
            >
          </figure>

          <input type="hidden" name="featured_image_existing" value="{{ old('featured_image_existing') }}" data-cover-existing-input>

          <div class="admin-upload-actions">
            <label class="admin-file-picker">
              <span>Choose an image</span>
              <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" data-cover-file-input>
            </label>

            <button type="button" class="admin-secondary-link" data-media-open="cover">Choose existing</button>
          </div>
          @error('featured_image')<small>{{ $message }}</small>@enderror
          @error('featured_image_existing')<small>{{ $message }}</small>@enderror

          @if ($post->featured_image_url)
            <label class="admin-checkbox">
              <input type="checkbox" name="remove_featured_image" value="1" data-cover-remove-input>
              <span>Remove current cover image</span>
            </label>
          @endif
        </section>
      </div>

      <div class="admin-editor-field">
        <span>Body (Markdown)</span>
        <div class="admin-editor" data-body-editor data-upload-url="{{ route('admin.posts.content-images.store') }}">
          <div class="admin-editor-toolbar" aria-label="Body formatting tools">
            <button type="button" class="admin-editor-button" data-editor-action="bold" aria-label="Bold"><strong>B</strong></button>
            <button type="button" class="admin-editor-button admin-editor-button--italic" data-editor-action="italic" aria-label="Italic"><em>I</em></button>
            <button type="button" class="admin-editor-button" data-editor-action="strike" aria-label="Strikethrough"><span>S</span></button>
            <button type="button" class="admin-editor-button" data-editor-action="inline-code" aria-label="Inline code">&lt;/&gt;</button>
            <button type="button" class="admin-editor-button" data-editor-action="link" aria-label="Insert link">↗</button>
            <button type="button" class="admin-editor-button" data-editor-action="heading" aria-label="Heading">H</button>
            <button type="button" class="admin-editor-button" data-editor-action="quote" aria-label="Quote">❝</button>
            <button type="button" class="admin-editor-button" data-editor-action="bullet-list" aria-label="Bullet list">•≡</button>
            <button type="button" class="admin-editor-button" data-editor-action="numbered-list" aria-label="Numbered list">1≡</button>

            <details class="admin-editor-menu">
              <summary class="admin-editor-button admin-editor-button--menu" aria-label="More options">+</summary>
              <div class="admin-editor-menu-panel">
                <button type="button" data-editor-action="code-block">Code Block</button>
                <button type="button" data-editor-action="image">Upload Image</button>
                <button type="button" data-editor-action="existing-image">Choose Existing</button>
              </div>
            </details>
          </div>

          <textarea name="body_markdown" rows="18" required data-body-markdown>{{ old('body_markdown', $post->body_markdown) }}</textarea>
          <input
            type="file"
            accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif"
            data-inline-image-input
            hidden
          >
        </div>
        <p class="admin-upload-feedback" data-inline-image-feedback hidden></p>
        <small>Use the toolbar to format text, insert code blocks, and upload body images.</small>
        @error('body_markdown')<small>{{ $message }}</small>@enderror
      </div>
    </div>
  </section>

  <section class="admin-panel">
    <div class="admin-panel-heading">
      <h2>Publishing</h2>
    </div>

    <div class="admin-form-fields">
      <label>
        <span>Categories</span>
        <input type="text" name="categories" value="{{ old('categories', $categoriesText) }}" placeholder="Product, Retail Guide" required>
        <small>Comma-separated. These drive the public filters.</small>
        @error('categories')<small>{{ $message }}</small>@enderror
      </label>

      <label>
        <span>Card label</span>
        <input type="text" name="card_label" value="{{ old('card_label', $post->card_label) }}" maxlength="50" required>
        @error('card_label')<small>{{ $message }}</small>@enderror
      </label>

      <div class="admin-color-grid">
        <label>
          <span>Card color 1</span>
          <input type="color" name="card_color_start" value="{{ old('card_color_start', $post->card_color_start ?? '#eaf7ff') }}">
          @error('card_color_start')<small>{{ $message }}</small>@enderror
        </label>

        <label>
          <span>Card color 2</span>
          <input type="color" name="card_color_mid" value="{{ old('card_color_mid', $post->card_color_mid ?? '#7fc8ff') }}">
          @error('card_color_mid')<small>{{ $message }}</small>@enderror
        </label>

        <label>
          <span>Card color 3</span>
          <input type="color" name="card_color_end" value="{{ old('card_color_end', $post->card_color_end ?? '#6e82ff') }}">
          @error('card_color_end')<small>{{ $message }}</small>@enderror
        </label>
      </div>

      <label>
        <span>Publish date & time</span>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\\TH:i')) }}">
        @error('published_at')<small>{{ $message }}</small>@enderror
      </label>

      <label>
        <span>Read time override</span>
        <input type="text" name="read_time_override" value="{{ old('read_time_override', $post->read_time_override) }}" placeholder="6:14">
        <small>Optional. Leave blank to auto-calculate.</small>
        @error('read_time_override')<small>{{ $message }}</small>@enderror
      </label>

      <label class="admin-checkbox">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
        <span>Publish this post publicly</span>
      </label>
    </div>
  </section>
</div>

<section class="admin-panel">
  <div class="admin-panel-heading">
    <h2>SEO</h2>
  </div>

  <div class="admin-form-fields">
    <label>
      <span>Meta title</span>
      <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="255">
      @error('meta_title')<small>{{ $message }}</small>@enderror
    </label>

    <label>
      <span>Meta description</span>
      <textarea name="meta_description" rows="3" maxlength="320">{{ old('meta_description', $post->meta_description) }}</textarea>
      @error('meta_description')<small>{{ $message }}</small>@enderror
    </label>
  </div>
</section>

@if ($isEditing)
  <section class="admin-panel">
    <div class="admin-panel-heading">
      <h2>URL management</h2>
    </div>

    <div class="admin-form-fields">
      <p class="admin-muted">Public URL: <a href="{{ $publicUrl }}" target="_blank" rel="noreferrer">{{ $publicUrl }}</a></p>

      @if (!empty($redirectSlugs) && $redirectSlugs->isNotEmpty())
        <div>
          <span class="admin-field-label">301 redirects</span>
          <ul class="admin-redirect-list">
            @foreach ($redirectSlugs as $redirectSlug)
              <li><code>/news/{{ $redirectSlug }}</code></li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
  </section>
@endif

<div class="admin-form-actions">
  <button type="submit" class="admin-primary-button">{{ $isEditing ? 'Save changes' : 'Create post' }}</button>

  @if ($isEditing)
    <button type="submit" form="delete-post-form" class="admin-danger-button">Delete post</button>
  @endif
</div>

<div class="admin-media-modal" data-media-modal data-library-url="{{ route('admin.media.images.index') }}" hidden>
  <div class="admin-media-dialog" role="dialog" aria-modal="true" aria-labelledby="admin-media-title">
    <div class="admin-media-header">
      <div>
        <h3 id="admin-media-title">Image library</h3>
        <p>Select a previously uploaded image.</p>
      </div>
      <button type="button" class="admin-media-close" data-media-close aria-label="Close image library">×</button>
    </div>

    <div class="admin-media-toolbar">
      <input type="search" class="admin-media-search" placeholder="Search images..." data-media-search>
    </div>

    <p class="admin-upload-feedback" data-media-feedback hidden></p>
    <p class="admin-media-empty" data-media-empty hidden>No uploaded images found yet.</p>
    <div class="admin-media-grid" data-media-grid></div>

    <div class="admin-media-actions">
      <button type="button" class="admin-secondary-link" data-media-close>Cancel</button>
      <button type="button" class="admin-primary-button" data-media-choose disabled>Choose selected</button>
    </div>
  </div>
</div>

<script>
  (() => {
    const titleInput = document.querySelector('[data-slug-source]');
    const slugInput = document.querySelector('[data-slug-target]');
    if (!titleInput || !slugInput) return;

    const slugify = (value) => value
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');

    let manualSlugEdit = slugInput.value.trim() !== '';

    slugInput.addEventListener('input', () => {
      manualSlugEdit = slugInput.value.trim() !== '';
    });

    titleInput.addEventListener('input', () => {
      if (manualSlugEdit) return;
      slugInput.value = slugify(titleInput.value);
    });
  })();

  (() => {
    const editor = document.querySelector('[data-body-editor]');
    const imageInput = document.querySelector('[data-inline-image-input]');
    const feedback = document.querySelector('[data-inline-image-feedback]');
    const bodyInput = document.querySelector('[data-body-markdown]');
    const csrfToken = document.querySelector('input[name="_token"]');
    const actionButtons = document.querySelectorAll('[data-editor-action]');
    const menu = document.querySelector('.admin-editor-menu');
    const mediaModal = document.querySelector('[data-media-modal]');
    const mediaGrid = document.querySelector('[data-media-grid]');
    const mediaSearch = document.querySelector('[data-media-search]');
    const mediaEmpty = document.querySelector('[data-media-empty]');
    const mediaFeedback = document.querySelector('[data-media-feedback]');
    const mediaChooseButton = document.querySelector('[data-media-choose]');
    const mediaCloseButtons = document.querySelectorAll('[data-media-close]');
    const mediaOpenButtons = document.querySelectorAll('[data-media-open]');
    const coverExistingInput = document.querySelector('[data-cover-existing-input]');
    const coverFileInput = document.querySelector('[data-cover-file-input]');
    const coverPreview = document.querySelector('[data-cover-preview]');
    const coverPreviewImage = document.querySelector('[data-cover-preview-image]');
    const coverRemoveInput = document.querySelector('[data-cover-remove-input]');

    if (!editor || !imageInput || !feedback || !bodyInput || !csrfToken) return;

    let mediaMode = null;
    let mediaImages = [];
    let selectedMediaImage = null;
    let mediaLoaded = false;

    const showFeedback = (message, isError = false) => {
      feedback.hidden = false;
      feedback.textContent = message;
      feedback.dataset.state = isError ? 'error' : 'success';
    };

    const showMediaFeedback = (message, isError = false) => {
      if (!mediaFeedback) return;

      mediaFeedback.hidden = false;
      mediaFeedback.textContent = message;
      mediaFeedback.dataset.state = isError ? 'error' : 'success';
    };

    const updateCoverPreview = (src = '', alt = 'Card image preview') => {
      if (!coverPreview || !coverPreviewImage) return;

      if (!src) {
        coverPreview.hidden = true;
        coverPreviewImage.removeAttribute('src');
        return;
      }

      coverPreview.hidden = false;
      coverPreviewImage.src = src;
      coverPreviewImage.alt = alt;
    };

    const replaceSelection = (replacement, selectionStartOffset = replacement.length, selectionEndOffset = replacement.length) => {
      const start = bodyInput.selectionStart ?? bodyInput.value.length;
      const end = bodyInput.selectionEnd ?? bodyInput.value.length;
      bodyInput.value = `${bodyInput.value.slice(0, start)}${replacement}${bodyInput.value.slice(end)}`;
      bodyInput.focus();
      bodyInput.setSelectionRange(start + selectionStartOffset, start + selectionEndOffset);
    };

    const wrapSelection = (prefix, suffix, placeholder) => {
      const start = bodyInput.selectionStart ?? bodyInput.value.length;
      const end = bodyInput.selectionEnd ?? bodyInput.value.length;
      const selectedText = bodyInput.value.slice(start, end);
      const content = selectedText || placeholder;
      const replacement = `${prefix}${content}${suffix}`;

      replaceSelection(replacement, prefix.length, prefix.length + content.length);
    };

    const prefixLines = (prefix, placeholder, numbered = false) => {
      const start = bodyInput.selectionStart ?? bodyInput.value.length;
      const end = bodyInput.selectionEnd ?? bodyInput.value.length;
      const selectedText = bodyInput.value.slice(start, end) || placeholder;
      const lines = selectedText.split('\n');
      const replacement = lines
        .map((line, index) => `${numbered ? `${index + 1}. ` : prefix}${line || placeholder}`)
        .join('\n');

      replaceSelection(replacement, 0, replacement.length);
    };

    const insertBlock = (content) => {
      const start = bodyInput.selectionStart ?? bodyInput.value.length;
      const end = bodyInput.selectionEnd ?? bodyInput.value.length;
      const prefix = start > 0 && !bodyInput.value.slice(0, start).endsWith('\n\n') ? '\n\n' : '';
      const suffix = end < bodyInput.value.length && !bodyInput.value.slice(end).startsWith('\n\n') ? '\n\n' : '';
      const replacement = `${prefix}${content}${suffix}`;

      replaceSelection(replacement, prefix.length, prefix.length + content.length);
    };

    const renderMediaGrid = () => {
      if (!mediaGrid || !mediaEmpty || !mediaChooseButton) return;

      const query = (mediaSearch?.value || '').trim().toLowerCase();
      const filteredImages = mediaImages.filter((image) => {
        return image.name.toLowerCase().includes(query) || image.alt.toLowerCase().includes(query);
      });

      mediaGrid.innerHTML = '';

      if (filteredImages.length === 0) {
        mediaEmpty.hidden = false;
        mediaChooseButton.disabled = true;
        return;
      }

      mediaEmpty.hidden = true;

      filteredImages.forEach((image) => {
        const card = document.createElement('button');
        card.type = 'button';
        card.className = 'admin-media-card';
        card.dataset.selected = selectedMediaImage?.url === image.url ? 'true' : 'false';

        const preview = document.createElement('div');
        preview.className = 'admin-media-card-preview';

        const img = document.createElement('img');
        img.src = image.url;
        img.alt = image.alt;
        preview.appendChild(img);

        const meta = document.createElement('div');
        meta.className = 'admin-media-card-meta';

        const title = document.createElement('strong');
        title.textContent = image.name;

        const section = document.createElement('span');
        section.textContent = image.section === 'covers' ? 'Card image' : 'Body image';

        meta.append(title, section);
        card.append(preview, meta);

        card.addEventListener('click', () => {
          selectedMediaImage = image;
          mediaChooseButton.disabled = false;
          renderMediaGrid();
        });

        mediaGrid.appendChild(card);
      });

      if (!filteredImages.some((image) => image.url === selectedMediaImage?.url)) {
        selectedMediaImage = null;
        mediaChooseButton.disabled = true;
      }
    };

    const loadMediaLibrary = async () => {
      if (mediaLoaded || !mediaModal) {
        renderMediaGrid();
        return;
      }

      showMediaFeedback('Loading images...');

      try {
        const response = await fetch(mediaModal.dataset.libraryUrl, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const payload = await response.json();

        if (!response.ok || !Array.isArray(payload.images)) {
          throw new Error(payload.message || 'Failed to load image library.');
        }

        mediaImages = payload.images;
        mediaLoaded = true;
        mediaFeedback.hidden = true;
        renderMediaGrid();
      } catch (error) {
        showMediaFeedback(error instanceof Error ? error.message : 'Failed to load image library.', true);
      }
    };

    const closeMediaModal = () => {
      if (!mediaModal) return;

      mediaModal.hidden = true;
      selectedMediaImage = null;
      mediaMode = null;
      if (mediaChooseButton) {
        mediaChooseButton.disabled = true;
      }
      renderMediaGrid();
    };

    const openMediaModal = async (mode) => {
      if (!mediaModal) return;

      mediaMode = mode;
      mediaModal.hidden = false;
      selectedMediaImage = null;
      if (mediaSearch) {
        mediaSearch.value = '';
      }
      if (mediaChooseButton) {
        mediaChooseButton.disabled = true;
      }
      await loadMediaLibrary();
    };

    const uploadSelectedImage = async () => {
      const selectedFile = imageInput.files?.[0];

      if (!selectedFile) {
        return;
      }

      showFeedback('Uploading image...');

      try {
        const formData = new FormData();
        formData.append('image', selectedFile);

        const response = await fetch(editor.dataset.uploadUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken.value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: formData,
        });

        const payload = await response.json();

        if (!response.ok || !payload.url) {
          throw new Error(payload.message || 'Upload failed.');
        }

        insertBlock(`![${payload.alt || 'News image'}](${payload.url})`);
        imageInput.value = '';
        mediaLoaded = false;
        showFeedback('Image uploaded and inserted into the article.');
      } catch (error) {
        showFeedback(error instanceof Error ? error.message : 'Upload failed.', true);
      }
    };

    actionButtons.forEach((button) => {
      button.addEventListener('click', () => {
        switch (button.dataset.editorAction) {
          case 'bold':
            wrapSelection('**', '**', 'Bold text');
            break;
          case 'italic':
            wrapSelection('*', '*', 'Italic text');
            break;
          case 'strike':
            wrapSelection('~~', '~~', 'Strikethrough text');
            break;
          case 'inline-code':
            wrapSelection('`', '`', 'inline code');
            break;
          case 'link': {
            const start = bodyInput.selectionStart ?? bodyInput.value.length;
            const end = bodyInput.selectionEnd ?? bodyInput.value.length;
            const selectedText = bodyInput.value.slice(start, end) || 'Link text';
            const replacement = `[${selectedText}](https://)`;
            const urlStart = replacement.indexOf('https://');
            replaceSelection(replacement, urlStart, urlStart + 'https://'.length);
            break;
          }
          case 'heading':
            insertBlock(`## ${bodyInput.value.slice(bodyInput.selectionStart ?? 0, bodyInput.selectionEnd ?? 0) || 'Heading'}`);
            break;
          case 'quote':
            prefixLines('> ', 'Quote');
            break;
          case 'bullet-list':
            prefixLines('- ', 'List item');
            break;
          case 'numbered-list':
            prefixLines('', 'List item', true);
            break;
          case 'code-block': {
            const start = bodyInput.selectionStart ?? bodyInput.value.length;
            const end = bodyInput.selectionEnd ?? bodyInput.value.length;
            const selectedText = bodyInput.value.slice(start, end) || "Code block";
            insertBlock(`\`\`\`\n${selectedText}\n\`\`\``);
            break;
          }
          case 'image':
            imageInput.click();
            break;
          case 'existing-image':
            void openMediaModal('body');
            break;
          default:
            break;
        }

        if (menu?.open) {
          menu.removeAttribute('open');
        }
      });
    });

    imageInput.addEventListener('change', () => {
      void uploadSelectedImage();
    });

    coverFileInput?.addEventListener('change', () => {
      if (!coverFileInput.files?.[0]) {
        return;
      }

      if (coverExistingInput) {
        coverExistingInput.value = '';
      }

      if (coverRemoveInput) {
        coverRemoveInput.checked = false;
      }

      const reader = new FileReader();
      const file = coverFileInput.files[0];

      reader.addEventListener('load', () => {
        updateCoverPreview(typeof reader.result === 'string' ? reader.result : '', file.name);
      });

      reader.readAsDataURL(file);
    });

    coverRemoveInput?.addEventListener('change', () => {
      if (coverRemoveInput.checked) {
        if (coverExistingInput) {
          coverExistingInput.value = '';
        }
        updateCoverPreview('');
        return;
      }

      updateCoverPreview(coverPreviewImage?.dataset.initialSrc || '');
    });

    mediaOpenButtons.forEach((button) => {
      button.addEventListener('click', () => {
        void openMediaModal(button.dataset.mediaOpen);
      });
    });

    mediaSearch?.addEventListener('input', renderMediaGrid);

    mediaChooseButton?.addEventListener('click', () => {
      if (!selectedMediaImage) {
        return;
      }

      if (mediaMode === 'cover') {
        if (coverExistingInput) {
          coverExistingInput.value = selectedMediaImage.url;
        }

        if (coverFileInput) {
          coverFileInput.value = '';
        }

        if (coverRemoveInput) {
          coverRemoveInput.checked = false;
        }

        if (coverPreviewImage) {
          coverPreviewImage.dataset.initialSrc = selectedMediaImage.url;
        }

        updateCoverPreview(selectedMediaImage.url, selectedMediaImage.alt);
      }

      if (mediaMode === 'body') {
        insertBlock(`![${selectedMediaImage.alt || 'News image'}](${selectedMediaImage.url})`);
        showFeedback('Existing image inserted into the article.');
      }

      closeMediaModal();
    });

    mediaCloseButtons.forEach((button) => {
      button.addEventListener('click', closeMediaModal);
    });

    document.addEventListener('click', (event) => {
      if (menu && menu.open && !menu.contains(event.target)) {
        menu.removeAttribute('open');
      }

      if (mediaModal && !mediaModal.hidden) {
        const dialog = mediaModal.querySelector('.admin-media-dialog');
        // Don't close the modal when the click came from any button
        // that just opened it (cover "Choose existing" with
        // [data-media-open], or the body menu "Choose Existing" with
        // [data-editor-action="existing-image"]). Without the second
        // selector, the body Choose Existing button would open the
        // modal and the bubbling click on document would close it
        // again on the same tick.
        const triggerSelector = '[data-media-open], [data-editor-action="existing-image"]';
        if (event.target === mediaModal || (dialog && !dialog.contains(event.target) && !event.target.closest(triggerSelector))) {
          closeMediaModal();
        }
      }
    });
  })();
</script>
