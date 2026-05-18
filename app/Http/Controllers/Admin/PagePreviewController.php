<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\CmsPreview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PagePreviewController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        abort_unless(array_key_exists($slug, PageController::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();
        $target = CmsPreview::normalizeTarget($slug, $request->query('target'));
        $previewContent = $request->session()->get(CmsPreview::sessionKey($slug), $page->effectiveDraft());

        app()->instance('cms.preview.payloads', [
            $slug => is_array($previewContent) ? $previewContent : $page->effectiveDraft(),
        ]);

        return view(CmsPreview::viewForTarget($target));
    }

    public function sync(Request $request, string $slug): JsonResponse
    {
        abort_unless(array_key_exists($slug, PageController::PAGES), 404);

        $page = Page::query()->where('slug', $slug)->firstOrFail();
        $content = $page->effectiveDraft();

        foreach ((array) $request->input('content', []) as $path => $value) {
            $value = $this->normaliseValue($value);
            Arr::set($content, (string) $path, $value);
        }

        foreach ((array) $request->input('remove_image', []) as $path) {
            Arr::forget($content, (string) $path);
        }

        $request->session()->put(CmsPreview::sessionKey($slug), $content);

        return response()->json([
            'ok' => true,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    private function normaliseValue(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(fn ($item) => $this->normaliseValue($item), $value);

            if (Arr::isList($value)) {
                $value = array_values(array_filter($value, function ($row) {
                    if (is_array($row)) {
                        return collect($row)->contains(fn ($item) => $item !== null && $item !== '');
                    }

                    return $row !== null && $row !== '';
                }));
            }

            return $value;
        }

        if (is_string($value)) {
            return trim($value);
        }

        return $value;
    }
}
