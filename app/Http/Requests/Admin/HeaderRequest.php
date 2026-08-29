<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class HeaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'logo_path' => trim((string) $this->input('logo_path')),
            'logo_alt' => trim((string) $this->input('logo_alt')),
            'logo_href' => trim((string) $this->input('logo_href')),
            'brand_name' => trim((string) $this->input('brand_name')),
            'brand_subtitle' => trim((string) $this->input('brand_subtitle')),
            'phone_label' => trim((string) $this->input('phone_label')),
            'phone_href' => trim((string) $this->input('phone_href')),
            'cta_label' => trim((string) $this->input('cta_label')),
            'cta_href' => trim((string) $this->input('cta_href')),
            'mobile_meta' => trim((string) $this->input('mobile_meta')),
            'nav_items' => $this->filledNavItems(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'logo_path' => ['nullable', 'string', 'max:2048', $this->assetPathRule()],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'logo_alt' => ['nullable', 'string', 'max:255'],
            'logo_href' => ['required', 'string', 'max:255', $this->hrefRule()],
            'brand_name' => ['required', 'string', 'max:80'],
            'brand_subtitle' => ['nullable', 'string', 'max:80'],
            'phone_label' => ['nullable', 'string', 'max:80'],
            'phone_href' => ['nullable', 'string', 'max:255', $this->hrefRule()],
            'cta_label' => ['required', 'string', 'max:80'],
            'cta_href' => ['required', 'string', 'max:255', $this->hrefRule()],
            'mobile_meta' => ['nullable', 'string', 'max:500'],

            'nav_items' => ['required', 'array', 'min:1', 'max:12'],
            'nav_items.*.label' => ['required', 'string', 'max:80'],
            'nav_items.*.href' => ['nullable', 'string', 'max:255', $this->hrefRule()],
            'nav_items.*.current_path' => ['nullable', 'string', 'max:255', $this->pathRule()],
            'nav_items.*.children' => ['array', 'max:12'],
            'nav_items.*.children.*.label' => ['required', 'string', 'max:80'],
            'nav_items.*.children.*.href' => ['required', 'string', 'max:255', $this->hrefRule()],
            'nav_items.*.children.*.current_path' => ['nullable', 'string', 'max:255', $this->pathRule()],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (filled($this->input('phone_label')) xor filled($this->input('phone_href'))) {
                $validator->errors()->add('phone_label', 'Provide both phone label and phone link, or leave both blank.');
            }

            foreach ($this->input('nav_items', []) as $index => $item) {
                if (! filled($item['href'] ?? null) && empty($item['children'] ?? [])) {
                    $validator->errors()->add("nav_items.{$index}.href", 'Provide a link or add dropdown items.');
                }
            }
        });
    }

    /**
     * @return list<array{label: string, href: string, current_path: string, children: list<array{label: string, href: string, current_path: string}>}>
     */
    private function filledNavItems(): array
    {
        $items = $this->input('nav_items', []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(function (array $item): array {
                $children = collect($item['children'] ?? [])
                    ->filter(fn (mixed $child): bool => is_array($child))
                    ->map(fn (array $child): array => [
                        'label' => trim((string) ($child['label'] ?? '')),
                        'href' => trim((string) ($child['href'] ?? '')),
                        'current_path' => trim((string) ($child['current_path'] ?? '')),
                    ])
                    ->filter(fn (array $child): bool => filled($child['label']) || filled($child['href']) || filled($child['current_path']))
                    ->values()
                    ->all();

                return [
                    'label' => trim((string) ($item['label'] ?? '')),
                    'href' => trim((string) ($item['href'] ?? '')),
                    'current_path' => trim((string) ($item['current_path'] ?? '')),
                    'children' => $children,
                ];
            })
            ->filter(fn (array $item): bool => filled($item['label']) || filled($item['href']) || filled($item['current_path']) || $item['children'] !== [])
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
            $blockedSchemes = ['javascript:', 'data:', 'vbscript:'];

            foreach ($blockedSchemes as $scheme) {
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

    private function pathRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $path = trim((string) $value);

            if ($path === '' || str_starts_with($path, '/')) {
                return;
            }

            $fail('Use a site path that starts with /.');
        };
    }
}
