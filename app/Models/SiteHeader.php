<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteHeader extends Model
{
    public const KEY = 'main';

    protected $fillable = [
        'key',
        'logo_path',
        'logo_alt',
        'logo_href',
        'brand_name',
        'brand_subtitle',
        'phone_label',
        'phone_href',
        'cta_label',
        'cta_href',
        'mobile_meta',
        'nav_items',
    ];

    protected function casts(): array
    {
        return [
            'nav_items' => 'array',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate(
            ['key' => self::KEY],
            self::defaultAttributes(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(): array
    {
        return [
            'key' => self::KEY,
            'logo_path' => '/assets/logo.png',
            'logo_alt' => '',
            'logo_href' => '/',
            'brand_name' => 'Pushpa Patel',
            'brand_subtitle' => 'Dental Clinic',
            'phone_label' => '+91 98200 00000',
            'phone_href' => 'tel:+919820000000',
            'cta_label' => 'Book appointment',
            'cta_href' => '#book',
            'mobile_meta' => "2nd Floor, Turner House, Linking Road, Bandra West, Mumbai 400050\nMon-Fri 9:30-19:30 | Sat 9:30-15:00",
            'nav_items' => [
                [
                    'label' => 'About Us',
                    'href' => '/about-us',
                    'current_path' => '/about-us',
                    'children' => [],
                ],
                [
                    'label' => 'Treatments',
                    'href' => '/#treatments',
                    'current_path' => '',
                    'children' => [],
                ],
                [
                    'label' => 'Doctors',
                    'href' => '/about-us#team',
                    'current_path' => '',
                    'children' => [],
                ],
                [
                    'label' => 'Reviews',
                    'href' => '/#reviews',
                    'current_path' => '',
                    'children' => [],
                ],
                [
                    'label' => 'Contact',
                    'href' => '/#contact',
                    'current_path' => '',
                    'children' => [],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultPublicArray(): array
    {
        return (new self(self::defaultAttributes()))->toPublicArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'logo_path' => $this->logo_path ?: '/assets/logo.png',
            'logo_alt' => $this->logo_alt ?: '',
            'logo_href' => $this->logo_href ?: '/',
            'brand_name' => $this->brand_name ?: 'Pushpa Patel',
            'brand_subtitle' => $this->brand_subtitle ?: 'Dental Clinic',
            'phone_label' => $this->phone_label ?: '',
            'phone_href' => $this->phone_href ?: '',
            'cta_label' => $this->cta_label ?: '',
            'cta_href' => $this->cta_href ?: '',
            'mobile_meta' => $this->mobile_meta ?: '',
            'nav_items' => $this->navItems(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'logo_path' => $this->logo_path ?: '',
            'logo_alt' => $this->logo_alt ?: '',
            'logo_href' => $this->logo_href ?: '/',
            'brand_name' => $this->brand_name ?: '',
            'brand_subtitle' => $this->brand_subtitle ?: '',
            'phone_label' => $this->phone_label ?: '',
            'phone_href' => $this->phone_href ?: '',
            'cta_label' => $this->cta_label ?: '',
            'cta_href' => $this->cta_href ?: '',
            'mobile_meta' => $this->mobile_meta ?: '',
            'nav_items' => $this->navItems(),
        ];
    }

    /**
     * @return list<array{label: string, href: string, current_path: string, children: list<array{label: string, href: string, current_path: string}>}>
     */
    private function navItems(): array
    {
        $items = is_array($this->nav_items) ? $this->nav_items : [];

        if ($items === []) {
            $items = self::defaultAttributes()['nav_items'];
        }

        return collect($items)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(function (array $item): array {
                return [
                    'label' => trim((string) ($item['label'] ?? '')),
                    'href' => trim((string) ($item['href'] ?? '')),
                    'current_path' => trim((string) ($item['current_path'] ?? '')),
                    'children' => $this->childItems($item['children'] ?? []),
                ];
            })
            ->filter(fn (array $item): bool => $item['label'] !== '' && ($item['href'] !== '' || $item['children'] !== []))
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, href: string, current_path: string}>
     */
    private function childItems(mixed $children): array
    {
        if (! is_array($children)) {
            return [];
        }

        return collect($children)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $item): array => [
                'label' => trim((string) ($item['label'] ?? '')),
                'href' => trim((string) ($item['href'] ?? '')),
                'current_path' => trim((string) ($item['current_path'] ?? '')),
            ])
            ->filter(fn (array $item): bool => $item['label'] !== '' && $item['href'] !== '')
            ->values()
            ->all();
    }
}
