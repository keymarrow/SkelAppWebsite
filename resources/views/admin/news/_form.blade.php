@php
  $isEditing = $post->exists;
  $publicUrl = $post->exists ? route('news.show', $post->slug) : null;
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

          @if ($post->featured_image_url)
            <figure class="admin-image-preview">
              <img src="{{ $post->featured_image_url }}" alt="{{ $post->title ?: 'Cover image preview' }}">
            </figure>
          @endif

          <label class="admin-file-picker">
            <span>Choose an image</span>
            <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif">
          </label>
          @error('featured_image')<small>{{ $message }}</small>@enderror

          @if ($post->featured_image_url)
            <label class="admin-checkbox">
              <input type="checkbox" name="remove_featured_image" value="1">
              <span>Remove current cover image</span>
            </label>
          @endif
        </section>
      </div>

      <label class="admin-editor-field">
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
                <button type="button" data-editor-action="image">Image</button>
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
      </label>
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

    if (!editor || !imageInput || !feedback || !bodyInput || !csrfToken) return;

    const showFeedback = (message, isError = false) => {
      feedback.hidden = false;
      feedback.textContent = message;
      feedback.dataset.state = isError ? 'error' : 'success';
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

    document.addEventListener('click', (event) => {
      if (menu && menu.open && !menu.contains(event.target)) {
        menu.removeAttribute('open');
      }
    });
  })();
</script>
