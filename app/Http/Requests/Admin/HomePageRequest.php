<?php

namespace App\Http\Requests\Admin;

use App\Models\HomePage;
use App\Support\RichTextSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class HomePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $sanitizer = app(RichTextSanitizer::class);

        $this->merge([
            'seo_robots_index' => $this->boolean('seo_robots_index'),
            'seo_robots_follow' => $this->boolean('seo_robots_follow'),
            'seo_enable_schema' => $this->boolean('seo_enable_schema'),
            'about_body' => $sanitizer->sanitize($this->input('about_body')),
            'contact_form_intro' => $sanitizer->sanitize($this->input('contact_form_intro')),
            'contact_form_success_body' => $sanitizer->sanitize($this->input('contact_form_success_body')),
            'hero_slides' => $this->filledRows(
                'hero_slides',
                [
                    'eyebrow',
                    'heading',
                    'heading_accent',
                    'copy',
                    'primary_label',
                    'primary_href',
                    'secondary_label',
                    'secondary_href',
                    'image',
                    'image_alt',
                    'dot',
                ],
                ['copy'],
            ),
            'hero_trust_items' => $this->filledRows('hero_trust_items', ['value', 'label']),
            'about_stats' => $this->filledRows('about_stats', ['value', 'label']),
            'stories_items' => $this->filledRows('stories_items', ['src', 'poster', 'name', 'tag']),
            'contact_form_treatment_options' => $this->filledRows('contact_form_treatment_options', ['label']),
            'contact_form_time_options' => $this->filledRows('contact_form_time_options', ['label']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero_slides' => ['required', 'array', 'min:1', 'max:6'],
            'hero_slides.*.eyebrow' => ['required', 'string', 'max:120'],
            'hero_slides.*.heading' => ['required', 'string', 'max:180'],
            'hero_slides.*.heading_accent' => ['nullable', 'string', 'max:120'],
            'hero_slides.*.copy' => ['required', 'string', 'max:1600'],
            'hero_slides.*.primary_label' => ['required', 'string', 'max:80'],
            'hero_slides.*.primary_href' => ['required', 'string', 'max:255'],
            'hero_slides.*.secondary_label' => ['nullable', 'string', 'max:80'],
            'hero_slides.*.secondary_href' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.image' => ['nullable', 'string', 'max:2048'],
            'hero_slides.*.image_alt' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.dot' => ['required', 'string', 'max:80'],
            'hero_slides.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'hero_trust_items' => ['required', 'array', 'min:1', 'max:6'],
            'hero_trust_items.*.value' => ['required', 'string', 'max:40'],
            'hero_trust_items.*.label' => ['required', 'string', 'max:120'],

            'about_eyebrow' => ['required', 'string', 'max:80'],
            'about_heading' => ['required', 'string', 'max:180'],
            'about_heading_accent' => ['nullable', 'string', 'max:120'],
            'about_body' => ['required', 'string', 'max:4000'],
            'about_cta_label' => ['required', 'string', 'max:80'],
            'about_cta_href' => ['required', 'string', 'max:255'],
            'about_stats' => ['required', 'array', 'min:1', 'max:8'],
            'about_stats.*.value' => ['required', 'string', 'max:40'],
            'about_stats.*.label' => ['required', 'string', 'max:120'],

            'stories_eyebrow' => ['required', 'string', 'max:80'],
            'stories_heading' => ['required', 'string', 'max:180'],
            'stories_heading_accent' => ['nullable', 'string', 'max:120'],
            'stories_items' => ['required', 'array', 'min:1', 'max:10'],
            'stories_items.*.src' => ['nullable', 'string', 'max:2048'],
            'stories_items.*.poster' => ['nullable', 'string', 'max:2048'],
            'stories_items.*.name' => ['required', 'string', 'max:120'],
            'stories_items.*.tag' => ['nullable', 'string', 'max:40'],
            'stories_items.*.video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:51200'],
            'stories_items.*.poster_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'contact_eyebrow' => ['required', 'string', 'max:80'],
            'contact_heading' => ['required', 'string', 'max:180'],
            'contact_heading_accent' => ['nullable', 'string', 'max:120'],
            'contact_map_title' => ['required', 'string', 'max:255'],
            'contact_map_src' => ['required', 'string', 'max:2048'],
            'contact_form_heading' => ['required', 'string', 'max:120'],
            'contact_form_intro' => ['required', 'string', 'max:1600'],
            'contact_form_treatment_options' => ['required', 'array', 'min:1', 'max:20'],
            'contact_form_treatment_options.*.label' => ['required', 'string', 'max:120'],
            'contact_form_time_options' => ['required', 'array', 'min:1', 'max:20'],
            'contact_form_time_options.*.label' => ['required', 'string', 'max:120'],
            'contact_form_submit_label' => ['required', 'string', 'max:80'],
            'contact_form_privacy_note' => ['nullable', 'string', 'max:300'],
            'contact_form_success_title' => ['required', 'string', 'max:160'],
            'contact_form_success_body' => ['required', 'string', 'max:1000'],

            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_canonical_url' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'seo_focus_keyword' => ['nullable', 'string', 'max:120'],
            'seo_secondary_keywords' => ['nullable', 'string', 'max:500'],
            'seo_robots_index' => ['required', 'boolean'],
            'seo_robots_follow' => ['required', 'boolean'],
            'seo_og_title' => ['nullable', 'string', 'max:180'],
            'seo_og_description' => ['nullable', 'string', 'max:300'],
            'seo_og_image' => ['nullable', 'string', 'max:2048'],
            'seo_og_image_alt' => ['nullable', 'string', 'max:255'],
            'seo_og_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'seo_twitter_card' => ['required', Rule::in(array_keys(HomePage::TWITTER_CARDS))],
            'seo_twitter_title' => ['nullable', 'string', 'max:180'],
            'seo_twitter_description' => ['nullable', 'string', 'max:300'],
            'seo_twitter_image' => ['nullable', 'string', 'max:2048'],
            'seo_twitter_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'seo_enable_schema' => ['required', 'boolean'],
            'seo_schema_type' => ['required', Rule::in(array_keys(HomePage::SCHEMA_TYPES))],
            'seo_schema_name' => ['nullable', 'string', 'max:180'],
            'seo_schema_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ($this->input('hero_slides', []) as $index => $slide) {
                if (! filled($slide['image'] ?? null) && ! $this->hasFile("hero_slides.{$index}.image_file")) {
                    $validator->errors()->add("hero_slides.{$index}.image", 'Provide an image path or upload an image.');
                }
            }

            foreach ($this->input('stories_items', []) as $index => $story) {
                if (! filled($story['src'] ?? null) && ! $this->hasFile("stories_items.{$index}.video_file")) {
                    $validator->errors()->add("stories_items.{$index}.src", 'Provide a video path or upload a video.');
                }

                if (! filled($story['poster'] ?? null) && ! $this->hasFile("stories_items.{$index}.poster_file")) {
                    $validator->errors()->add("stories_items.{$index}.poster", 'Provide a poster image path or upload an image.');
                }
            }
        });
    }

    /**
     * @param  list<string>  $fields
     * @param  list<string>  $richFields
     * @return list<array<string, string>>
     */
    private function filledRows(string $key, array $fields, array $richFields = []): array
    {
        $rows = $this->input($key, []);
        $sanitizer = app(RichTextSanitizer::class);

        if (! is_array($rows)) {
            return [];
        }

        return collect($rows)
            ->filter(function (mixed $row) use ($fields): bool {
                if (! is_array($row)) {
                    return false;
                }

                return collect($fields)->contains(fn (string $field): bool => filled($row[$field] ?? null));
            })
            ->map(function (array $row) use ($fields, $richFields, $sanitizer): array {
                return collect($fields)
                    ->mapWithKeys(fn (string $field): array => [
                        $field => in_array($field, $richFields, true)
                            ? $sanitizer->sanitize($row[$field] ?? null)
                            : trim((string) ($row[$field] ?? '')),
                    ])
                    ->all();
            })
            ->values()
            ->all();
    }
}
