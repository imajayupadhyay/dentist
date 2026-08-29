<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteFooter extends Model
{
    public const KEY = 'main';

    public const CTA_ICONS = [
        'phone' => 'Phone',
        'whatsapp' => 'WhatsApp',
        'arrow' => 'Arrow',
        'link' => 'Link',
    ];

    public const CTA_ACTION_VARIANTS = [
        'primary' => 'White button',
        'whatsapp' => 'Translucent button',
    ];

    public const SOCIAL_ICONS = [
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'youtube' => 'YouTube',
        'map' => 'Map',
        'whatsapp' => 'WhatsApp',
        'link' => 'Link',
    ];

    public const CONTACT_ICONS = [
        'location' => 'Location',
        'phone' => 'Phone',
        'email' => 'Email',
        'clock' => 'Clock',
        'link' => 'Link',
    ];

    public const LINK_GROUP_SOURCES = [
        'manual' => 'Manual links',
        'treatments' => 'Active treatments',
    ];

    protected $fillable = [
        'key',
        'cta_enabled',
        'cta_icon',
        'cta_title',
        'cta_body',
        'cta_actions',
        'logo_path',
        'logo_alt',
        'brand_name',
        'brand_subtitle',
        'brand_blurb',
        'social_links',
        'link_groups',
        'contact_title',
        'contact_items',
        'bottom_copyright',
        'bottom_location',
        'back_to_top_label',
        'back_to_top_href',
    ];

    protected function casts(): array
    {
        return [
            'cta_enabled' => 'boolean',
            'cta_actions' => 'array',
            'social_links' => 'array',
            'link_groups' => 'array',
            'contact_items' => 'array',
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
            'cta_enabled' => true,
            'cta_icon' => 'phone',
            'cta_title' => 'Dental emergency?',
            'cta_body' => 'A slot is held every weekday afternoon. Call before noon and you will be seen today.',
            'cta_actions' => [
                [
                    'label' => 'Call +91 98200 00000',
                    'href' => 'tel:+919820000000',
                    'variant' => 'primary',
                    'icon' => 'arrow',
                    'aria_label' => '',
                ],
                [
                    'label' => 'WhatsApp',
                    'href' => 'https://wa.me/919820000000?text=Hello%2C%20I%27d%20like%20to%20book%20a%20dental%20appointment.',
                    'variant' => 'whatsapp',
                    'icon' => 'whatsapp',
                    'aria_label' => 'Message the clinic on WhatsApp',
                ],
            ],
            'logo_path' => '/assets/logo.png',
            'logo_alt' => '',
            'brand_name' => 'Pushpa Patel',
            'brand_subtitle' => 'Dental Clinic',
            'brand_blurb' => 'Modern, unhurried dentistry in Bandra West since 2009. Implants, aligners, smile design, jaw joint care and full-mouth rehabilitation, all under one roof.',
            'social_links' => [
                ['label' => 'Instagram', 'href' => '#', 'icon' => 'instagram'],
                ['label' => 'Facebook', 'href' => '#', 'icon' => 'facebook'],
                ['label' => 'YouTube', 'href' => '#', 'icon' => 'youtube'],
                ['label' => 'Find us on Google Maps', 'href' => '#', 'icon' => 'map'],
            ],
            'link_groups' => [
                [
                    'title' => 'Treatments',
                    'source' => 'treatments',
                    'links' => [
                        ['label' => 'Painless dentistry', 'href' => '/#treatments'],
                        ['label' => 'Dental implants', 'href' => '/#treatments'],
                        ['label' => 'Invisible aligners', 'href' => '/#treatments'],
                        ['label' => 'Smile design', 'href' => '/#treatments'],
                        ['label' => 'Jaw joint & TMD', 'href' => '/#treatments'],
                        ['label' => "Kids' dentistry", 'href' => '/#treatments'],
                    ],
                ],
                [
                    'title' => 'Clinic',
                    'source' => 'manual',
                    'links' => [
                        ['label' => 'About the clinic', 'href' => '/about-us'],
                        ['label' => 'Our dentists', 'href' => '/about-us#team'],
                        ['label' => 'Patient stories', 'href' => '/#stories'],
                        ['label' => 'Google reviews', 'href' => '/#reviews'],
                        ['label' => 'Find us', 'href' => '/#contact'],
                        ['label' => 'Book an appointment', 'href' => '/#book'],
                    ],
                ],
            ],
            'contact_title' => 'Visit us',
            'contact_items' => [
                [
                    'icon' => 'location',
                    'label' => "2nd Floor, Turner House,\nLinking Road, Bandra West,\nMumbai 400050",
                    'href' => '',
                ],
                [
                    'icon' => 'phone',
                    'label' => '+91 22 2600 0000',
                    'href' => 'tel:+912226000000',
                ],
                [
                    'icon' => 'email',
                    'label' => 'care@pushpapateldental.in',
                    'href' => 'mailto:care@pushpapateldental.in',
                ],
                [
                    'icon' => 'clock',
                    'label' => "Mon–Fri 9:30–19:30\nSat 9:30–15:00",
                    'href' => '',
                ],
            ],
            'bottom_copyright' => "© {year} Dr. Pushpa Patel's Dental Clinic. All rights reserved.",
            'bottom_location' => 'Bandra West, Mumbai',
            'back_to_top_label' => 'Back to top',
            'back_to_top_href' => '#top',
        ];
    }

    /**
     * @return array<string, list<array{value: string, label: string}>>
     */
    public static function options(): array
    {
        return [
            'cta_icons' => self::optionsFromMap(self::CTA_ICONS),
            'cta_action_variants' => self::optionsFromMap(self::CTA_ACTION_VARIANTS),
            'social_icons' => self::optionsFromMap(self::SOCIAL_ICONS),
            'contact_icons' => self::optionsFromMap(self::CONTACT_ICONS),
            'link_group_sources' => self::optionsFromMap(self::LINK_GROUP_SOURCES),
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
            'cta_enabled' => $this->cta_enabled,
            'cta_icon' => $this->cta_icon ?: 'phone',
            'cta_title' => $this->cta_title ?: '',
            'cta_body' => $this->cta_body ?: '',
            'cta_actions' => $this->ctaActions(),
            'logo_path' => $this->logo_path ?: '/assets/logo.png',
            'logo_alt' => $this->logo_alt ?: '',
            'brand_name' => $this->brand_name ?: 'Pushpa Patel',
            'brand_subtitle' => $this->brand_subtitle ?: 'Dental Clinic',
            'brand_blurb' => $this->brand_blurb ?: '',
            'social_links' => $this->socialLinks(),
            'link_groups' => $this->linkGroups(),
            'contact_title' => $this->contact_title ?: '',
            'contact_items' => $this->contactItems(),
            'bottom_copyright' => $this->bottom_copyright ?: '',
            'bottom_location' => $this->bottom_location ?: '',
            'back_to_top_label' => $this->back_to_top_label ?: '',
            'back_to_top_href' => $this->back_to_top_href ?: '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'cta_enabled' => $this->cta_enabled,
            'cta_icon' => $this->cta_icon ?: 'phone',
            'cta_title' => $this->cta_title ?: '',
            'cta_body' => $this->cta_body ?: '',
            'cta_actions' => $this->ctaActions(),
            'logo_path' => $this->logo_path ?: '',
            'logo_alt' => $this->logo_alt ?: '',
            'brand_name' => $this->brand_name ?: '',
            'brand_subtitle' => $this->brand_subtitle ?: '',
            'brand_blurb' => $this->brand_blurb ?: '',
            'social_links' => $this->socialLinks(),
            'link_groups' => $this->linkGroups(),
            'contact_title' => $this->contact_title ?: '',
            'contact_items' => $this->contactItems(),
            'bottom_copyright' => $this->bottom_copyright ?: '',
            'bottom_location' => $this->bottom_location ?: '',
            'back_to_top_label' => $this->back_to_top_label ?: '',
            'back_to_top_href' => $this->back_to_top_href ?: '',
        ];
    }

    /**
     * @param  array<string, string>  $map
     * @return list<array{value: string, label: string}>
     */
    private static function optionsFromMap(array $map): array
    {
        return collect($map)
            ->map(fn (string $label, string $value): array => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, href: string, variant: string, icon: string, aria_label: string}>
     */
    private function ctaActions(): array
    {
        $actions = is_array($this->cta_actions) ? $this->cta_actions : [];

        if ($actions === []) {
            $actions = self::defaultAttributes()['cta_actions'];
        }

        return collect($actions)
            ->filter(fn (mixed $action): bool => is_array($action))
            ->map(fn (array $action): array => [
                'label' => trim((string) ($action['label'] ?? '')),
                'href' => trim((string) ($action['href'] ?? '')),
                'variant' => array_key_exists($action['variant'] ?? '', self::CTA_ACTION_VARIANTS)
                    ? (string) $action['variant']
                    : 'primary',
                'icon' => array_key_exists($action['icon'] ?? '', self::CTA_ICONS)
                    ? (string) $action['icon']
                    : 'arrow',
                'aria_label' => trim((string) ($action['aria_label'] ?? '')),
            ])
            ->filter(fn (array $action): bool => $action['label'] !== '' && $action['href'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, href: string, icon: string}>
     */
    private function socialLinks(): array
    {
        return collect(is_array($this->social_links) ? $this->social_links : [])
            ->filter(fn (mixed $link): bool => is_array($link))
            ->map(fn (array $link): array => [
                'label' => trim((string) ($link['label'] ?? '')),
                'href' => trim((string) ($link['href'] ?? '')),
                'icon' => array_key_exists($link['icon'] ?? '', self::SOCIAL_ICONS)
                    ? (string) $link['icon']
                    : 'link',
            ])
            ->filter(fn (array $link): bool => $link['label'] !== '' && $link['href'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<array{title: string, source: string, links: list<array{label: string, href: string}>}>
     */
    private function linkGroups(): array
    {
        $groups = is_array($this->link_groups) ? $this->link_groups : [];

        if ($groups === []) {
            $groups = self::defaultAttributes()['link_groups'];
        }

        return collect($groups)
            ->filter(fn (mixed $group): bool => is_array($group))
            ->map(fn (array $group): array => [
                'title' => trim((string) ($group['title'] ?? '')),
                'source' => array_key_exists($group['source'] ?? '', self::LINK_GROUP_SOURCES)
                    ? (string) $group['source']
                    : 'manual',
                'links' => $this->links($group['links'] ?? []),
            ])
            ->filter(fn (array $group): bool => $group['title'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, href: string}>
     */
    private function links(mixed $links): array
    {
        if (! is_array($links)) {
            return [];
        }

        return collect($links)
            ->filter(fn (mixed $link): bool => is_array($link))
            ->map(fn (array $link): array => [
                'label' => trim((string) ($link['label'] ?? '')),
                'href' => trim((string) ($link['href'] ?? '')),
            ])
            ->filter(fn (array $link): bool => $link['label'] !== '' && $link['href'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<array{icon: string, label: string, href: string}>
     */
    private function contactItems(): array
    {
        return collect(is_array($this->contact_items) ? $this->contact_items : [])
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $item): array => [
                'icon' => array_key_exists($item['icon'] ?? '', self::CONTACT_ICONS)
                    ? (string) $item['icon']
                    : 'link',
                'label' => trim((string) ($item['label'] ?? '')),
                'href' => trim((string) ($item['href'] ?? '')),
            ])
            ->filter(fn (array $item): bool => $item['label'] !== '')
            ->values()
            ->all();
    }
}
