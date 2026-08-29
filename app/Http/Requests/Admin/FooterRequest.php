<?php

namespace App\Http\Requests\Admin;

use App\Models\SiteFooter;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class FooterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cta_enabled' => $this->boolean('cta_enabled'),
            'cta_icon' => trim((string) $this->input('cta_icon')),
            'cta_title' => trim((string) $this->input('cta_title')),
            'cta_body' => trim((string) $this->input('cta_body')),
            'logo_path' => trim((string) $this->input('logo_path')),
            'logo_alt' => trim((string) $this->input('logo_alt')),
            'brand_name' => trim((string) $this->input('brand_name')),
            'brand_subtitle' => trim((string) $this->input('brand_subtitle')),
            'brand_blurb' => trim((string) $this->input('brand_blurb')),
            'contact_title' => trim((string) $this->input('contact_title')),
            'bottom_copyright' => trim((string) $this->input('bottom_copyright')),
            'bottom_location' => trim((string) $this->input('bottom_location')),
            'back_to_top_label' => trim((string) $this->input('back_to_top_label')),
            'back_to_top_href' => trim((string) $this->input('back_to_top_href')),
            'cta_actions' => $this->filledRows('cta_actions', ['label', 'href', 'variant', 'icon', 'aria_label']),
            'social_links' => $this->filledRows('social_links', ['label', 'href', 'icon']),
            'link_groups' => $this->filledLinkGroups(),
            'contact_items' => $this->filledRows('contact_items', ['icon', 'label', 'href']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cta_enabled' => ['required', 'boolean'],
            'cta_icon' => ['required', Rule::in(array_keys(SiteFooter::CTA_ICONS))],
            'cta_title' => [Rule::requiredIf(fn (): bool => $this->boolean('cta_enabled')), 'nullable', 'string', 'max:120'],
            'cta_body' => [Rule::requiredIf(fn (): bool => $this->boolean('cta_enabled')), 'nullable', 'string', 'max:400'],
            'cta_actions' => ['array', 'max:4'],
            'cta_actions.*.label' => ['required', 'string', 'max:80'],
            'cta_actions.*.href' => ['required', 'string', 'max:255', $this->hrefRule()],
            'cta_actions.*.variant' => ['required', Rule::in(array_keys(SiteFooter::CTA_ACTION_VARIANTS))],
            'cta_actions.*.icon' => ['required', Rule::in(array_keys(SiteFooter::CTA_ICONS))],
            'cta_actions.*.aria_label' => ['nullable', 'string', 'max:160'],

            'logo_path' => ['nullable', 'string', 'max:2048', $this->assetPathRule()],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'logo_alt' => ['nullable', 'string', 'max:255'],
            'brand_name' => ['required', 'string', 'max:80'],
            'brand_subtitle' => ['nullable', 'string', 'max:80'],
            'brand_blurb' => ['nullable', 'string', 'max:500'],

            'social_links' => ['array', 'max:12'],
            'social_links.*.label' => ['required', 'string', 'max:80'],
            'social_links.*.href' => ['required', 'string', 'max:255', $this->hrefRule()],
            'social_links.*.icon' => ['required', Rule::in(array_keys(SiteFooter::SOCIAL_ICONS))],

            'link_groups' => ['required', 'array', 'min:1', 'max:8'],
            'link_groups.*.title' => ['required', 'string', 'max:80'],
            'link_groups.*.source' => ['required', Rule::in(array_keys(SiteFooter::LINK_GROUP_SOURCES))],
            'link_groups.*.links' => ['array', 'max:24'],
            'link_groups.*.links.*.label' => ['required', 'string', 'max:80'],
            'link_groups.*.links.*.href' => ['required', 'string', 'max:255', $this->hrefRule()],

            'contact_title' => ['nullable', 'string', 'max:80'],
            'contact_items' => ['array', 'max:12'],
            'contact_items.*.icon' => ['required', Rule::in(array_keys(SiteFooter::CONTACT_ICONS))],
            'contact_items.*.label' => ['required', 'string', 'max:300'],
            'contact_items.*.href' => ['nullable', 'string', 'max:255', $this->hrefRule()],

            'bottom_copyright' => ['required', 'string', 'max:180'],
            'bottom_location' => ['nullable', 'string', 'max:120'],
            'back_to_top_label' => ['nullable', 'string', 'max:80'],
            'back_to_top_href' => ['nullable', 'string', 'max:255', $this->hrefRule()],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->boolean('cta_enabled') && count($this->input('cta_actions', [])) === 0) {
                $validator->errors()->add('cta_actions', 'Add at least one CTA action or disable the CTA section.');
            }

            foreach ($this->input('link_groups', []) as $index => $group) {
                if (($group['source'] ?? null) === 'manual' && empty($group['links'] ?? [])) {
                    $validator->errors()->add("link_groups.{$index}.links", 'Manual link groups need at least one link.');
                }
            }

            if (filled($this->input('back_to_top_label')) xor filled($this->input('back_to_top_href'))) {
                $validator->errors()->add('back_to_top_label', 'Provide both back-to-top label and link, or leave both blank.');
            }
        });
    }

    /**
     * @param  list<string>  $fields
     * @return list<array<string, string>>
     */
    private function filledRows(string $key, array $fields): array
    {
        $rows = $this->input($key, []);

        if (! is_array($rows)) {
            return [];
        }

        return collect($rows)
            ->filter(fn (mixed $row): bool => is_array($row))
            ->map(fn (array $row): array => collect($fields)
                ->mapWithKeys(fn (string $field): array => [
                    $field => trim((string) ($row[$field] ?? '')),
                ])
                ->all())
            ->filter(fn (array $row): bool => collect($fields)->contains(fn (string $field): bool => filled($row[$field] ?? null)))
            ->values()
            ->all();
    }

    /**
     * @return list<array{title: string, source: string, links: list<array{label: string, href: string}>}>
     */
    private function filledLinkGroups(): array
    {
        $groups = $this->input('link_groups', []);

        if (! is_array($groups)) {
            return [];
        }

        return collect($groups)
            ->filter(fn (mixed $group): bool => is_array($group))
            ->map(function (array $group): array {
                return [
                    'title' => trim((string) ($group['title'] ?? '')),
                    'source' => trim((string) ($group['source'] ?? 'manual')),
                    'links' => collect($group['links'] ?? [])
                        ->filter(fn (mixed $link): bool => is_array($link))
                        ->map(fn (array $link): array => [
                            'label' => trim((string) ($link['label'] ?? '')),
                            'href' => trim((string) ($link['href'] ?? '')),
                        ])
                        ->filter(fn (array $link): bool => filled($link['label']) || filled($link['href']))
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn (array $group): bool => filled($group['title']) || $group['links'] !== [])
            ->values()
            ->all();
    }

    private function hrefRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $href = trim((string) $value);

            if ($href === '') {
                return;
            }

            $lower = strtolower($href);

            foreach (['javascript:', 'data:', 'vbscript:'] as $scheme) {
                if (str_starts_with($lower, $scheme)) {
                    $fail('This link type is not allowed.');

                    return;
                }
            }

            foreach (['/', '#', 'http://', 'https://', 'mailto:', 'tel:'] as $prefix) {
                if (str_starts_with($lower, $prefix)) {
                    return;
                }
            }

            $fail('Use an internal path, hash, phone, email, or http(s) link.');
        };
    }

    private function assetPathRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $path = trim((string) $value);

            if ($path === '') {
                return;
            }

            $lower = strtolower($path);

            foreach (['javascript:', 'data:', 'vbscript:'] as $scheme) {
                if (str_starts_with($lower, $scheme)) {
                    $fail('This asset path is not allowed.');

                    return;
                }
            }

            foreach (['/', 'http://', 'https://'] as $prefix) {
                if (str_starts_with($lower, $prefix)) {
                    return;
                }
            }

            $fail('Use an absolute public path or http(s) asset URL.');
        };
    }
}
