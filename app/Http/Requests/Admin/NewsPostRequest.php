<?php

namespace App\Http\Requests\Admin;

use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class NewsPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var NewsPost|null $post */
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('news_posts', 'slug')->ignore($post?->id),
            ],
            'summary' => ['required', 'string', 'max:500'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'featured_image_existing' => ['nullable', 'string', 'max:2048'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'body_markdown' => ['required', 'string'],
            'categories' => ['required', 'string', 'max:500'],
            'card_label' => ['required', 'string', 'max:50'],
            'card_color_start' => ['required', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'card_color_mid' => ['required', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'card_color_end' => ['required', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'read_time_override' => ['nullable', 'regex:/^\d{1,2}:\d{2}$/'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var NewsPost|null $post */
            $post = $this->route('post');
            $slug = $this->string('slug')->toString();

            $redirectExists = NewsPostSlugRedirect::query()
                ->where('slug', $slug)
                ->when($post, fn ($query) => $query->where('news_post_id', '!=', $post->id))
                ->exists();

            if ($redirectExists) {
                $validator->errors()->add('slug', 'This slug is already reserved by a redirect.');
            }

            if (count($this->parsedCategories()) === 0) {
                $validator->errors()->add('categories', 'Add at least one category.');
            }
        });
    }

    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'summary' => $validated['summary'],
            'body_markdown' => $validated['body_markdown'],
            'categories' => $this->parsedCategories(),
            'card_label' => $validated['card_label'],
            'card_color_start' => $validated['card_color_start'],
            'card_color_mid' => $validated['card_color_mid'],
            'card_color_end' => $validated['card_color_end'],
            'meta_title' => Arr::get($validated, 'meta_title'),
            'meta_description' => Arr::get($validated, 'meta_description'),
            'read_time_override' => Arr::get($validated, 'read_time_override'),
            'is_published' => $this->boolean('is_published'),
            'published_at' => Arr::get($validated, 'published_at') ?: null,
        ];
    }

    public function parsedCategories(): array
    {
        return collect(preg_split('/[\r\n,]+/', $this->string('categories')->toString()) ?: [])
            ->map(fn (string $category) => trim($category))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
