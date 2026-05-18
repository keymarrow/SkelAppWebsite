<aside class="admin-live-preview-panel">
  <div class="admin-live-preview-card">
    <div class="admin-live-preview-header">
      <div class="admin-live-preview-copy">
        <h2>Article preview</h2>
        <p>See the public article while you write. Text and metadata update live. Uploaded images appear after saving.</p>
      </div>

      <div class="admin-live-preview-actions">
        <button type="button" class="admin-secondary-link" data-news-preview-refresh>Refresh</button>
        <a href="{{ $previewUrl }}" target="_blank" rel="noreferrer" class="admin-secondary-link" data-news-preview-open>Open in tab</a>
      </div>
    </div>

    <div class="admin-live-preview-meta">
      <span class="admin-live-preview-badge">Admin-only preview</span>
      <span class="admin-live-preview-status" data-news-preview-status>Preview ready.</span>
    </div>

    <div class="admin-live-preview-frame-wrap">
      <iframe
        src="{{ $previewUrl }}"
        class="admin-live-preview-frame"
        title="News article preview"
        data-news-preview-frame
      ></iframe>
    </div>
  </div>
</aside>
