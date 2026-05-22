<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\CmsPreview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Pages that can be edited in the CMS, in the order they appear in the sidebar.
     * Each entry maps slug → ['label' => 'Sidebar label', 'view' => 'admin partial used to render the form'].
     */
    public const PAGES = [
        'home' => ['label' => 'Home', 'view' => 'admin.pages.forms.home'],
        'features' => ['label' => 'Features', 'view' => 'admin.pages.forms.features'],
        'pricing' => ['label' => 'Pricing', 'view' => 'admin.pages.forms.pricing'],
        'faq' => ['label' => 'FAQ', 'view' => 'admin.pages.forms.faq'],
        'contact' => ['label' => 'Contact', 'view' => 'admin.pages.forms.contact'],
        'terms' => ['label' => 'Terms', 'view' => 'admin.pages.forms.terms'],
        'global' => ['label' => 'Footer & Nav', 'view' => 'admin.pages.forms.global'],
    ];

    public function edit(string $slug): View
    {
        abort_unless(array_key_exists($slug, self::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();

        return view('admin.pages.edit', [
            'page' => $page,
            'slug' => $slug,
            'config' => self::PAGES[$slug],
            'content' => $page->effectiveDraft(),
            'previewTargets' => CmsPreview::targetsFor($slug),
            'defaultPreviewTarget' => CmsPreview::defaultTarget($slug),
        ]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        abort_unless(array_key_exists($slug, self::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();

        $content = $this->prepareContent($request, $page);

        $page->update([
            'draft_content' => $content,
            'draft_updated_by_id' => auth('admin')->id(),
        ]);

        $message = 'Draft saved.';

        if ($request->boolean('publish')) {
            $page->update([
                'published_content' => $content,
                'published_at' => now(),
                'published_by_id' => auth('admin')->id(),
            ]);
            $page->forgetCache();
            $message = 'Page published.';
        } else {
            $page->forgetCache();
        }

        session()->forget(CmsPreview::sessionKey($slug));

        return redirect()
            ->route('admin.pages.edit', $slug)
            ->with('status', $message);
    }

    public function publish(string $slug): RedirectResponse
    {
        abort_unless(array_key_exists($slug, self::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();

        if ($page->draft_content) {
            $page->update([
                'published_content' => $page->draft_content,
                'published_at' => now(),
                'published_by_id' => auth('admin')->id(),
            ]);
            $page->forgetCache();
        }

        session()->forget(CmsPreview::sessionKey($slug));

        return redirect()
            ->route('admin.pages.edit', $slug)
            ->with('status', 'Page published.');
    }

    /**
     * AJAX image upload — used by the in-form image picker. Stores the file in
     * the public cms/ disk and returns the URL the form should save.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:8192'],
        ]);

        /** @var UploadedFile $image */
        $image = $request->file('image');
        $url = $this->storeUploadedImage($image, 'cms');
        $alt = Str::of($image->getClientOriginalName())
            ->beforeLast('.')
            ->replace(['-', '_'], ' ')
            ->trim()
            ->title()
            ->toString();

        return response()->json([
            'url' => $url,
            'alt' => $alt !== '' ? $alt : 'Image',
        ]);
    }

    public function revert(string $slug): RedirectResponse
    {
        abort_unless(array_key_exists($slug, self::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();

        $page->update([
            'draft_content' => $page->published_content,
        ]);
        $page->forgetCache();
        session()->forget(CmsPreview::sessionKey($slug));

        return redirect()
            ->route('admin.pages.edit', $slug)
            ->with('status', 'Draft reverted to last published version.');
    }

    /**
     * Merge submitted form fields back onto the existing content payload, and
     * persist any uploaded images into storage. Form keys come in two flavours:
     *  - `content[dot.path]` → set Arr::set($content, 'dot.path', $value)
     *  - `image[dot.path]` (file) → upload + Arr::set the public URL
     */
    private function prepareContent(Request $request, Page $page): array
    {
        $content = $page->effectiveDraft();

        // Plain text/textarea/repeater fields submitted as content[*]
        foreach ((array) $request->input('content', []) as $path => $value) {
            $value = $this->normaliseValue($value);
            Arr::set($content, $path, $value);
        }

        // File uploads: image[dot.path] → store file → Arr::set URL into content
        foreach ($request->allFiles() as $rootKey => $files) {
            if ($rootKey !== 'image') {
                continue;
            }
            if (! is_array($files)) {
                continue;
            }
            $this->processImageUploads($files, '', $content);
        }

        // Image removals: remove_image[]= dot.path
        foreach ((array) $request->input('remove_image', []) as $path) {
            Arr::forget($content, (string) $path);
        }

        return $content;
    }

    private function processImageUploads(array $files, string $prefix, array &$content): void
    {
        foreach ($files as $key => $value) {
            $path = $prefix === '' ? (string) $key : $prefix.'.'.$key;
            if ($value instanceof UploadedFile) {
                $url = $this->storeUploadedImage($value, 'cms');
                if ($url) {
                    Arr::set($content, $path, $url);
                }
            } elseif (is_array($value)) {
                $this->processImageUploads($value, $path, $content);
            }
        }
    }

    private function storeUploadedImage(UploadedFile $image, string $directory): ?string
    {
        $extension = strtolower($image->getClientOriginalExtension() ?: 'jpg');
        $filename = now()->format('YmdHis').'-'.Str::random(12).'.'.$extension;
        $path = $image->storeAs($directory, $filename, 'public');

        return asset('storage/'.ltrim($path, '/'));
    }

    /**
     * Recursively trim strings (leave booleans/numbers/arrays alone) and drop
     * empty repeater rows whose every value is blank.
     */
    private function normaliseValue(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(fn ($v) => $this->normaliseValue($v), $value);

            // For numerically-indexed arrays (repeaters), drop fully-empty rows.
            if (Arr::isList($value)) {
                $value = array_values(array_filter($value, function ($row) {
                    if (is_array($row)) {
                        return collect($row)->contains(fn ($v) => $v !== null && $v !== '');
                    }
                    return $row !== null && $row !== '';
                }));
            }

            return $value;
        }

        if (is_string($value)) {
            // Preserve newlines, just trim outer whitespace.
            return trim($value);
        }

        return $value;
    }
}
