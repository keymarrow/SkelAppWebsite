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

      <label>
        <span>Body (Markdown)</span>
        <textarea name="body_markdown" rows="18" required>{{ old('body_markdown', $post->body_markdown) }}</textarea>
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
</script>
