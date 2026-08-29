<?php

namespace App\Http\Requests\Admin;

use App\Models\Treatment;
use App\Support\RichTextSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $title = (string) ($this->input('title') ?: $this->input('home_title'));
        $sanitizer = app(RichTextSanitizer::class);

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'slug' => Str::slug($this->input('slug') ?: $title),
            'whatsapp_number' => preg_replace('/\D+/', '', (string) $this->input('whatsapp_number')),
            'seo_robots_index' => $this->boolean('seo_robots_index'),
            'seo_robots_follow' => $this->boolean('seo_robots_follow'),
            'seo_enable_schema' => $this->boolean('seo_enable_schema'),
            'summary' => $sanitizer->sanitize($this->input('summary')),
            'overview_lede' => $sanitizer->sanitize($this->input('overview_lede')),
            'overview_body' => $sanitizer->sanitize($this->input('overview_body')),
            'suitability_lede' => $sanitizer->sanitize($this->input('suitability_lede')),
            'process_lede' => $sanitizer->sanitize($this->input('process_lede')),
            'faq_lede' => $sanitizer->sanitize($this->input('faq_lede')),
            'cta_body' => $sanitizer->sanitize($this->input('cta_body')),
            'facts' => $this->filledRows('facts', ['label', 'value']),
            'suitable_for' => $this->filledRows('suitable_for', ['text']),
            'not_suitable' => $this->filledRows('not_suitable', ['text']),
            'steps' => $this->filledRows('steps', ['title', 'duration', 'body'], ['body']),
            'faqs' => $this->filledRows('faqs', ['question', 'answer'], ['answer']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $treatment = $this->route('treatment');

        return [
            'sort_order' => ['required', 'integer', 'min:1', 'max:999'],
            'is_active' => ['required', 'boolean'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:160',
                Rule::unique('treatments', 'slug')->ignore($treatment?->getKey()),
            ],
            'tone' => ['required', Rule::in(array_keys(Treatment::TONES))],

            'home_title' => ['required', 'string', 'max:120'],
            'home_subtitle' => ['required', 'string', 'max:160'],
            'home_description' => ['required', 'string', 'max:700'],
            'home_image' => ['nullable', 'string', 'max:255'],
            'home_image_alt' => ['nullable', 'string', 'max:255'],
            'home_icon_svg' => ['required', 'string', 'max:4000'],
            'home_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'category' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:160'],
            'title_accent' => ['nullable', 'string', 'max:80'],
            'tagline' => ['required', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:1200'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'facts' => ['required', 'array', 'min:1', 'max:6'],
            'facts.*.label' => ['required', 'string', 'max:80'],
            'facts.*.value' => ['required', 'string', 'max:80'],

            'overview_eyebrow' => ['required', 'string', 'max:80'],
            'overview_heading' => ['required', 'string', 'max:180'],
            'overview_heading_accent' => ['nullable', 'string', 'max:100'],
            'overview_lede' => ['required', 'string', 'max:1200'],
            'overview_body' => ['required', 'string', 'max:8000'],
            'overview_image' => ['nullable', 'string', 'max:255'],
            'overview_image_alt' => ['nullable', 'string', 'max:255'],
            'overview_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'overview_caption' => ['nullable', 'string', 'max:500'],

            'suitability_eyebrow' => ['required', 'string', 'max:80'],
            'suitability_heading' => ['required', 'string', 'max:180'],
            'suitability_heading_accent' => ['nullable', 'string', 'max:100'],
            'suitability_lede' => ['nullable', 'string', 'max:1200'],
            'suitable_for' => ['required', 'array', 'min:1', 'max:12'],
            'suitable_for.*.text' => ['required', 'string', 'max:300'],
            'not_suitable' => ['required', 'array', 'min:1', 'max:12'],
            'not_suitable.*.text' => ['required', 'string', 'max:300'],

            'process_eyebrow' => ['required', 'string', 'max:80'],
            'process_heading' => ['required', 'string', 'max:180'],
            'process_heading_accent' => ['nullable', 'string', 'max:100'],
            'process_lede' => ['nullable', 'string', 'max:1200'],
            'steps' => ['required', 'array', 'min:1', 'max:10'],
            'steps.*.title' => ['required', 'string', 'max:140'],
            'steps.*.duration' => ['nullable', 'string', 'max:80'],
            'steps.*.body' => ['required', 'string', 'max:1000'],

            'faq_eyebrow' => ['required', 'string', 'max:80'],
            'faq_heading' => ['required', 'string', 'max:180'],
            'faq_heading_accent' => ['nullable', 'string', 'max:100'],
            'faq_lede' => ['nullable', 'string', 'max:1200'],
            'faqs' => ['required', 'array', 'min:1', 'max:10'],
            'faqs.*.question' => ['required', 'string', 'max:180'],
            'faqs.*.answer' => ['required', 'string', 'max:2000'],

            'cta_heading' => ['required', 'string', 'max:180'],
            'cta_heading_accent' => ['nullable', 'string', 'max:100'],
            'cta_body' => ['nullable', 'string', 'max:1000'],
            'whatsapp_number' => ['required', 'digits_between:8,16'],
            'whatsapp_message' => ['nullable', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:40'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_canonical_url' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'seo_focus_keyword' => ['nullable', 'string', 'max:120'],
            'seo_secondary_keywords' => ['nullable', 'string', 'max:500'],
            'seo_robots_index' => ['required', 'boolean'],
            'seo_robots_follow' => ['required', 'boolean'],
            'seo_breadcrumb_label' => ['nullable', 'string', 'max:120'],
            'seo_og_title' => ['nullable', 'string', 'max:180'],
            'seo_og_description' => ['nullable', 'string', 'max:300'],
            'seo_og_image' => ['nullable', 'string', 'max:2048'],
            'seo_og_image_alt' => ['nullable', 'string', 'max:255'],
            'seo_og_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'seo_twitter_card' => ['required', Rule::in(array_keys(Treatment::TWITTER_CARDS))],
            'seo_twitter_title' => ['nullable', 'string', 'max:180'],
            'seo_twitter_description' => ['nullable', 'string', 'max:300'],
            'seo_twitter_image' => ['nullable', 'string', 'max:2048'],
            'seo_twitter_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'seo_enable_schema' => ['required', 'boolean'],
            'seo_schema_type' => ['required', Rule::in(array_keys(Treatment::SCHEMA_TYPES))],
            'seo_schema_name' => ['nullable', 'string', 'max:180'],
            'seo_schema_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ([
                'home_image' => 'home_image_file',
                'hero_image' => 'hero_image_file',
                'overview_image' => 'overview_image_file',
            ] as $pathField => $fileField) {
                if (! $this->filled($pathField) && ! $this->hasFile($fileField)) {
                    $validator->errors()->add($pathField, 'Provide an image path or upload an image.');
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
