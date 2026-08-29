<?php

namespace App\Http\Requests\Admin;

use App\Models\AboutPage;
use App\Support\RichTextSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AboutPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $sanitizer = app(RichTextSanitizer::class);

        $this->merge([
            'masthead_proof_stars' => (int) $this->input('masthead_proof_stars', 5),
            'seo_robots_index' => $this->boolean('seo_robots_index'),
            'seo_robots_follow' => $this->boolean('seo_robots_follow'),
            'seo_enable_schema' => $this->boolean('seo_enable_schema'),
            'masthead_lede' => $sanitizer->sanitize($this->input('masthead_lede')),
            'note_body' => $sanitizer->sanitize($this->input('note_body')),
            'values_lede' => $sanitizer->sanitize($this->input('values_lede')),
            'team_lede' => $sanitizer->sanitize($this->input('team_lede')),
            'cta_body' => $sanitizer->sanitize($this->input('cta_body')),
            'masthead_meta' => $this->filledRows('masthead_meta', ['text']),
            'figures' => $this->filledRows('figures', ['count', 'decimals', 'suffix', 'prefix', 'value', 'label']),
            'values_items' => $this->filledRows('values_items', ['num', 'title', 'copy'], ['copy']),
            'clinicians' => $this->filledRows('clinicians', ['name', 'role']),
            'team_chips' => $this->filledRows('team_chips', ['text']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'masthead_eyebrow' => ['required', 'string', 'max:80'],
            'masthead_heading' => ['required', 'string', 'max:180'],
            'masthead_heading_accent' => ['nullable', 'string', 'max:120'],
            'masthead_heading_suffix' => ['nullable', 'string', 'max:120'],
            'masthead_lede' => ['required', 'string', 'max:1600'],
            'masthead_meta' => ['required', 'array', 'min:1', 'max:6'],
            'masthead_meta.*.text' => ['required', 'string', 'max:120'],
            'masthead_primary_label' => ['required', 'string', 'max:80'],
            'masthead_primary_href' => ['required', 'string', 'max:255'],
            'masthead_secondary_label' => ['nullable', 'string', 'max:80'],
            'masthead_secondary_href' => ['nullable', 'string', 'max:255'],
            'masthead_lead_image' => ['nullable', 'string', 'max:2048'],
            'masthead_lead_image_alt' => ['nullable', 'string', 'max:255'],
            'masthead_lead_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'masthead_inset_image' => ['nullable', 'string', 'max:2048'],
            'masthead_inset_image_alt' => ['nullable', 'string', 'max:255'],
            'masthead_inset_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'masthead_proof_stars' => ['required', 'integer', 'min:0', 'max:5'],
            'masthead_proof_rating' => ['required', 'string', 'max:80'],
            'masthead_proof_text' => ['required', 'string', 'max:120'],

            'figures' => ['required', 'array', 'min:1', 'max:8'],
            'figures.*.count' => ['required', 'numeric'],
            'figures.*.decimals' => ['nullable', 'integer', 'min:0', 'max:3'],
            'figures.*.suffix' => ['nullable', 'string', 'max:20'],
            'figures.*.prefix' => ['nullable', 'string', 'max:20'],
            'figures.*.value' => ['required', 'string', 'max:40'],
            'figures.*.label' => ['required', 'string', 'max:120'],

            'note_eyebrow' => ['required', 'string', 'max:80'],
            'note_image' => ['nullable', 'string', 'max:2048'],
            'note_image_alt' => ['nullable', 'string', 'max:255'],
            'note_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'note_quote' => ['required', 'string', 'max:1000'],
            'note_body' => ['required', 'string', 'max:4000'],
            'note_signature' => ['required', 'string', 'max:80'],
            'note_name' => ['required', 'string', 'max:120'],
            'note_role' => ['required', 'string', 'max:180'],

            'values_eyebrow' => ['required', 'string', 'max:80'],
            'values_heading' => ['required', 'string', 'max:180'],
            'values_heading_accent' => ['nullable', 'string', 'max:120'],
            'values_heading_suffix' => ['nullable', 'string', 'max:120'],
            'values_lede' => ['required', 'string', 'max:1600'],
            'values_items' => ['required', 'array', 'min:1', 'max:8'],
            'values_items.*.num' => ['required', 'string', 'max:12'],
            'values_items.*.title' => ['required', 'string', 'max:140'],
            'values_items.*.copy' => ['required', 'string', 'max:1200'],

            'team_eyebrow' => ['required', 'string', 'max:80'],
            'team_heading' => ['required', 'string', 'max:180'],
            'team_heading_accent' => ['nullable', 'string', 'max:120'],
            'team_heading_suffix' => ['nullable', 'string', 'max:120'],
            'team_lede' => ['required', 'string', 'max:1600'],
            'team_image' => ['nullable', 'string', 'max:2048'],
            'team_image_alt' => ['nullable', 'string', 'max:255'],
            'team_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'team_caption' => ['nullable', 'string', 'max:600'],
            'clinicians' => ['required', 'array', 'min:1', 'max:16'],
            'clinicians.*.name' => ['required', 'string', 'max:120'],
            'clinicians.*.role' => ['required', 'string', 'max:180'],
            'team_chips' => ['required', 'array', 'min:1', 'max:16'],
            'team_chips.*.text' => ['required', 'string', 'max:120'],

            'cta_heading' => ['required', 'string', 'max:180'],
            'cta_heading_accent' => ['nullable', 'string', 'max:120'],
            'cta_heading_suffix' => ['nullable', 'string', 'max:120'],
            'cta_body' => ['required', 'string', 'max:1600'],
            'cta_primary_label' => ['required', 'string', 'max:80'],
            'cta_primary_href' => ['required', 'string', 'max:255'],
            'cta_secondary_label' => ['nullable', 'string', 'max:80'],
            'cta_secondary_href' => ['nullable', 'string', 'max:255'],

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
            'seo_twitter_card' => ['required', Rule::in(array_keys(AboutPage::TWITTER_CARDS))],
            'seo_twitter_title' => ['nullable', 'string', 'max:180'],
            'seo_twitter_description' => ['nullable', 'string', 'max:300'],
            'seo_twitter_image' => ['nullable', 'string', 'max:2048'],
            'seo_twitter_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'seo_enable_schema' => ['required', 'boolean'],
            'seo_schema_type' => ['required', Rule::in(array_keys(AboutPage::SCHEMA_TYPES))],
            'seo_schema_name' => ['nullable', 'string', 'max:180'],
            'seo_schema_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ([
                'masthead_lead_image' => 'masthead_lead_image_file',
                'masthead_inset_image' => 'masthead_inset_image_file',
                'note_image' => 'note_image_file',
                'team_image' => 'team_image_file',
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
