<?php

namespace App\Models;

use App\Support\RichTextSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Treatment extends Model
{
    public const TONES = [
        'crimson' => [
            'label' => 'Crimson',
            'home_theme' => '--bg:#cf0039;--fg:#ffffff;--sub:rgba(255,255,255,.95);--accent:#ffe0e8',
        ],
        'ink' => [
            'label' => 'Ink',
            'home_theme' => '--bg:#16162a;--fg:#ffffff;--sub:rgba(255,255,255,.84);--accent:#ffc24d',
        ],
        'gold' => [
            'label' => 'Gold',
            'home_theme' => '--bg:#ffc24d;--fg:#1b1b2b;--sub:rgba(27,27,43,.84);--accent:#a3130a',
        ],
        'mint' => [
            'label' => 'Mint',
            'home_theme' => '--bg:#00c2a0;--fg:#04372c;--sub:rgba(4,55,44,.95);--accent:#053026',
        ],
        'violet' => [
            'label' => 'Violet',
            'home_theme' => '--bg:#5a37e0;--fg:#ffffff;--sub:rgba(255,255,255,.93);--accent:#ffd76b',
        ],
        'blush' => [
            'label' => 'Blush',
            'home_theme' => '--bg:#fff0e4;--fg:#1b1b2b;--sub:rgba(27,27,43,.82);--accent:#c40036',
        ],
    ];

    public const TWITTER_CARDS = [
        'summary_large_image' => 'Large image card',
        'summary' => 'Summary card',
    ];

    public const SCHEMA_TYPES = [
        'MedicalProcedure' => 'Medical procedure',
        'MedicalTherapy' => 'Medical therapy',
        'Service' => 'Service',
    ];

    private const RICH_TEXT_FIELDS = [
        'summary',
        'overview_lede',
        'overview_body',
        'suitability_lede',
        'process_lede',
        'faq_lede',
        'cta_body',
    ];

    private const RICH_TEXT_ROW_FIELDS = [
        'steps' => ['body'],
        'faqs' => ['answer'],
    ];

    protected $fillable = [
        'sort_order',
        'is_active',
        'slug',
        'tone',
        'home_title',
        'home_subtitle',
        'home_description',
        'home_image',
        'home_image_alt',
        'home_icon_svg',
        'category',
        'title',
        'title_accent',
        'tagline',
        'summary',
        'hero_image',
        'hero_image_alt',
        'facts',
        'overview_eyebrow',
        'overview_heading',
        'overview_heading_accent',
        'overview_lede',
        'overview_body',
        'overview_image',
        'overview_image_alt',
        'overview_caption',
        'suitability_eyebrow',
        'suitability_heading',
        'suitability_heading_accent',
        'suitability_lede',
        'suitable_for',
        'not_suitable',
        'process_eyebrow',
        'process_heading',
        'process_heading_accent',
        'process_lede',
        'steps',
        'faq_eyebrow',
        'faq_heading',
        'faq_heading_accent',
        'faq_lede',
        'faqs',
        'cta_heading',
        'cta_heading_accent',
        'cta_body',
        'whatsapp_number',
        'whatsapp_message',
        'phone',
        'seo_title',
        'seo_description',
        'seo_canonical_url',
        'seo_focus_keyword',
        'seo_secondary_keywords',
        'seo_robots_index',
        'seo_robots_follow',
        'seo_breadcrumb_label',
        'seo_og_title',
        'seo_og_description',
        'seo_og_image',
        'seo_og_image_alt',
        'seo_twitter_card',
        'seo_twitter_title',
        'seo_twitter_description',
        'seo_twitter_image',
        'seo_enable_schema',
        'seo_schema_type',
        'seo_schema_name',
        'seo_schema_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'seo_robots_index' => 'boolean',
            'seo_robots_follow' => 'boolean',
            'seo_enable_schema' => 'boolean',
            'facts' => 'array',
            'suitable_for' => 'array',
            'not_suitable' => 'array',
            'steps' => 'array',
            'faqs' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public static function toneOptions(): array
    {
        return collect(self::TONES)
            ->map(fn (array $tone, string $value): array => [
                'value' => $value,
                'label' => $tone['label'],
            ])
            ->values()
            ->all();
    }

    public static function emptyAdminArray(): array
    {
        return [
            'id' => null,
            'sort_order' => 1,
            'is_active' => true,
            'slug' => '',
            'tone' => 'crimson',
            'home_title' => '',
            'home_subtitle' => '',
            'home_description' => '',
            'home_image' => '',
            'home_image_alt' => '',
            'home_icon_svg' => '<circle cx="12" cy="12" r="9"/>',
            'category' => '',
            'title' => '',
            'title_accent' => '',
            'tagline' => '',
            'summary' => '',
            'hero_image' => '',
            'hero_image_alt' => '',
            'facts' => [['label' => '', 'value' => '']],
            'overview_eyebrow' => 'What it is',
            'overview_heading' => '',
            'overview_heading_accent' => '',
            'overview_lede' => '',
            'overview_body' => '',
            'overview_image' => '',
            'overview_image_alt' => '',
            'overview_caption' => '',
            'suitability_eyebrow' => 'Suitability',
            'suitability_heading' => 'Who this works for.',
            'suitability_heading_accent' => '',
            'suitability_lede' => '',
            'suitable_for' => [['text' => '']],
            'not_suitable' => [['text' => '']],
            'process_eyebrow' => 'Step by step',
            'process_heading' => 'From first visit to finished care.',
            'process_heading_accent' => '',
            'process_lede' => '',
            'steps' => [['title' => '', 'duration' => '', 'body' => '']],
            'faq_eyebrow' => 'Questions',
            'faq_heading' => 'The things people actually ask.',
            'faq_heading_accent' => '',
            'faq_lede' => '',
            'faqs' => [['question' => '', 'answer' => '']],
            'cta_heading' => 'Find out if it is right for you.',
            'cta_heading_accent' => '',
            'cta_body' => '',
            'whatsapp_number' => '919820000000',
            'whatsapp_message' => '',
            'phone' => '+912226000000',
            'seo_title' => '',
            'seo_description' => '',
            'seo_canonical_url' => '',
            'seo_focus_keyword' => '',
            'seo_secondary_keywords' => '',
            'seo_robots_index' => true,
            'seo_robots_follow' => true,
            'seo_breadcrumb_label' => '',
            'seo_og_title' => '',
            'seo_og_description' => '',
            'seo_og_image' => '',
            'seo_og_image_alt' => '',
            'seo_twitter_card' => 'summary_large_image',
            'seo_twitter_title' => '',
            'seo_twitter_description' => '',
            'seo_twitter_image' => '',
            'seo_enable_schema' => true,
            'seo_schema_type' => 'MedicalProcedure',
            'seo_schema_name' => '',
            'seo_schema_description' => '',
        ];
    }

    /**
     * @return array<string, list<array{value: string, label: string}>>
     */
    public static function seoOptions(): array
    {
        return [
            'twitter_cards' => self::optionsFromMap(self::TWITTER_CARDS),
            'schema_types' => self::optionsFromMap(self::SCHEMA_TYPES),
        ];
    }

    public function toHomeCard(): array
    {
        return [
            'num' => str_pad((string) $this->sort_order, 2, '0', STR_PAD_LEFT),
            'slug' => $this->slug,
            'href' => "/treatments/{$this->slug}",
            'title' => $this->home_title,
            'sub' => $this->home_subtitle,
            'copy' => $this->home_description,
            'image' => $this->home_image,
            'alt' => $this->home_image_alt,
            'theme' => self::TONES[$this->tone]['home_theme'] ?? self::TONES['crimson']['home_theme'],
            'icon' => $this->home_icon_svg,
        ];
    }

    public function toDetailPage(): array
    {
        return [
            ...$this->toAdminArray(),
            'seo_meta' => $this->toSeoMeta(),
            'public_url' => url("/treatments/{$this->slug}"),
            'created_at_iso' => $this->created_at?->toIso8601String(),
            'updated_at_iso' => $this->updated_at?->toIso8601String(),
            'whatsapp_url' => $this->whatsappUrl(),
            'phone_href' => $this->phoneHref(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSeoMeta(): array
    {
        $title = $this->seo_title ?: $this->title;
        $description = $this->seo_description ?: $this->plainText($this->summary);
        $canonical = $this->seo_canonical_url ?: url("/treatments/{$this->slug}");
        $ogImage = $this->absoluteUrl($this->seo_og_image ?: $this->hero_image ?: $this->home_image);
        $twitterImage = $this->absoluteUrl($this->seo_twitter_image ?: $this->seo_og_image ?: $this->hero_image ?: $this->home_image);
        $ogTitle = $this->seo_og_title ?: $title;
        $ogDescription = $this->seo_og_description ?: $description;
        $keywords = collect([$this->seo_focus_keyword, $this->seo_secondary_keywords])
            ->filter(fn (?string $value): bool => filled($value))
            ->implode(', ');

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => collect([
                $this->seo_robots_index === false ? 'noindex' : 'index',
                $this->seo_robots_follow === false ? 'nofollow' : 'follow',
                'max-image-preview:large',
                'max-snippet:-1',
                'max-video-preview:-1',
            ])->implode(','),
            'keywords' => $keywords,
            'og' => [
                'site_name' => "Dr. Pushpa Patel's Dental Clinic",
                'type' => 'article',
                'title' => $ogTitle,
                'description' => $ogDescription,
                'url' => $canonical,
                'image' => $ogImage,
                'image_alt' => $this->seo_og_image_alt ?: $this->hero_image_alt ?: $this->home_image_alt ?: $ogTitle,
            ],
            'twitter' => [
                'card' => $this->seo_twitter_card ?: 'summary_large_image',
                'title' => $this->seo_twitter_title ?: $ogTitle,
                'description' => $this->seo_twitter_description ?: $ogDescription,
                'image' => $twitterImage,
            ],
            'article' => [
                'published_time' => $this->created_at?->toIso8601String(),
                'modified_time' => $this->updated_at?->toIso8601String(),
            ],
            'json_ld' => $this->seo_enable_schema ? $this->jsonLd() : '',
        ];
    }

    public function toRelatedCard(): array
    {
        return [
            'title' => $this->home_title,
            'blurb' => $this->home_description,
            'image' => $this->home_image,
            'alt' => $this->home_image_alt,
            'url' => "/treatments/{$this->slug}",
            'tone' => $this->tone,
        ];
    }

    public function toAdminArray(): array
    {
        return $this->sanitizeRichFields([
            'id' => $this->id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'slug' => $this->slug,
            'tone' => $this->tone,
            'home_title' => $this->home_title,
            'home_subtitle' => $this->home_subtitle,
            'home_description' => $this->home_description,
            'home_image' => $this->home_image,
            'home_image_alt' => $this->home_image_alt,
            'home_icon_svg' => $this->home_icon_svg,
            'category' => $this->category,
            'title' => $this->title,
            'title_accent' => $this->title_accent,
            'tagline' => $this->tagline,
            'summary' => $this->summary,
            'hero_image' => $this->hero_image,
            'hero_image_alt' => $this->hero_image_alt,
            'facts' => $this->facts ?: [],
            'overview_eyebrow' => $this->overview_eyebrow,
            'overview_heading' => $this->overview_heading,
            'overview_heading_accent' => $this->overview_heading_accent,
            'overview_lede' => $this->overview_lede,
            'overview_body' => $this->overview_body,
            'overview_image' => $this->overview_image,
            'overview_image_alt' => $this->overview_image_alt,
            'overview_caption' => $this->overview_caption,
            'suitability_eyebrow' => $this->suitability_eyebrow,
            'suitability_heading' => $this->suitability_heading,
            'suitability_heading_accent' => $this->suitability_heading_accent,
            'suitability_lede' => $this->suitability_lede,
            'suitable_for' => $this->suitable_for ?: [],
            'not_suitable' => $this->not_suitable ?: [],
            'process_eyebrow' => $this->process_eyebrow,
            'process_heading' => $this->process_heading,
            'process_heading_accent' => $this->process_heading_accent,
            'process_lede' => $this->process_lede,
            'steps' => $this->steps ?: [],
            'faq_eyebrow' => $this->faq_eyebrow,
            'faq_heading' => $this->faq_heading,
            'faq_heading_accent' => $this->faq_heading_accent,
            'faq_lede' => $this->faq_lede,
            'faqs' => $this->faqs ?: [],
            'cta_heading' => $this->cta_heading,
            'cta_heading_accent' => $this->cta_heading_accent,
            'cta_body' => $this->cta_body,
            'whatsapp_number' => $this->whatsapp_number,
            'whatsapp_message' => $this->whatsapp_message,
            'phone' => $this->phone,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_canonical_url' => $this->seo_canonical_url,
            'seo_focus_keyword' => $this->seo_focus_keyword,
            'seo_secondary_keywords' => $this->seo_secondary_keywords,
            'seo_robots_index' => $this->seo_robots_index,
            'seo_robots_follow' => $this->seo_robots_follow,
            'seo_breadcrumb_label' => $this->seo_breadcrumb_label,
            'seo_og_title' => $this->seo_og_title,
            'seo_og_description' => $this->seo_og_description,
            'seo_og_image' => $this->seo_og_image,
            'seo_og_image_alt' => $this->seo_og_image_alt,
            'seo_twitter_card' => $this->seo_twitter_card,
            'seo_twitter_title' => $this->seo_twitter_title,
            'seo_twitter_description' => $this->seo_twitter_description,
            'seo_twitter_image' => $this->seo_twitter_image,
            'seo_enable_schema' => $this->seo_enable_schema,
            'seo_schema_type' => $this->seo_schema_type,
            'seo_schema_name' => $this->seo_schema_name,
            'seo_schema_description' => $this->seo_schema_description,
        ]);
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
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitizeRichFields(array $data): array
    {
        $sanitizer = app(RichTextSanitizer::class);

        foreach (self::RICH_TEXT_FIELDS as $field) {
            $data[$field] = $sanitizer->sanitize($data[$field] ?? null);
        }

        foreach (self::RICH_TEXT_ROW_FIELDS as $rowKey => $fields) {
            if (! is_array($data[$rowKey] ?? null)) {
                continue;
            }

            $data[$rowKey] = collect($data[$rowKey])
                ->map(function (array $row) use ($fields, $sanitizer): array {
                    foreach ($fields as $field) {
                        $row[$field] = $sanitizer->sanitize($row[$field] ?? null);
                    }

                    return $row;
                })
                ->all();
        }

        return $data;
    }

    private function whatsappUrl(): string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp_number ?? '');
        $message = $this->whatsapp_message ?: "Hello, I'd like to ask about {$this->home_title}.";

        return "https://wa.me/{$number}?text=".rawurlencode($message);
    }

    private function phoneHref(): string
    {
        return 'tel:'.preg_replace('/[^\d+]+/', '', $this->phone ?? '');
    }

    private function jsonLd(): string
    {
        $clinicUrl = rtrim(url('/'), '/');
        $canonical = $this->seo_canonical_url ?: url("/treatments/{$this->slug}");
        $title = $this->seo_title ?: $this->title;
        $description = $this->seo_description ?: $this->plainText($this->summary);
        $image = $this->absoluteUrl($this->seo_og_image ?: $this->hero_image ?: $this->home_image);
        $breadcrumbLabel = $this->seo_breadcrumb_label ?: $this->home_title ?: $this->title;
        $schemaType = $this->seo_schema_type ?: 'MedicalProcedure';
        $schemaName = $this->seo_schema_name ?: $this->title ?: $this->home_title;
        $schemaDescription = $this->seo_schema_description ?: $description;

        $graph = [
            $this->compactSchema([
                '@type' => 'WebSite',
                '@id' => "{$clinicUrl}#website",
                'name' => "Dr. Pushpa Patel's Dental Clinic",
                'url' => "{$clinicUrl}/",
            ]),
            $this->compactSchema([
                '@type' => 'WebPage',
                '@id' => "{$canonical}#webpage",
                'url' => $canonical,
                'name' => $title,
                'description' => $description,
                'datePublished' => $this->created_at?->toIso8601String(),
                'dateModified' => $this->updated_at?->toIso8601String(),
                'isPartOf' => ['@id' => "{$clinicUrl}#website"],
                'primaryImageOfPage' => $image ? ['@id' => "{$canonical}#primaryimage"] : null,
                'breadcrumb' => ['@id' => "{$canonical}#breadcrumb"],
            ]),
            $this->compactSchema([
                '@type' => 'ImageObject',
                '@id' => "{$canonical}#primaryimage",
                'url' => $image,
                'caption' => $this->seo_og_image_alt ?: $this->hero_image_alt ?: $this->home_image_alt,
            ]),
            [
                '@type' => 'BreadcrumbList',
                '@id' => "{$canonical}#breadcrumb",
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => "{$clinicUrl}/",
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Treatments',
                        'item' => "{$clinicUrl}/#treatments",
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $breadcrumbLabel,
                        'item' => $canonical,
                    ],
                ],
            ],
            $this->compactSchema([
                '@type' => $schemaType,
                '@id' => "{$canonical}#treatment",
                'name' => $schemaName,
                'description' => $schemaDescription,
                'image' => $image,
                'url' => $canonical,
                'provider' => [
                    '@type' => 'Dentist',
                    'name' => "Dr. Pushpa Patel's Dental Clinic",
                    'url' => "{$clinicUrl}/",
                    'telephone' => '+912226000000',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '2nd Floor, Turner House, Linking Road, Bandra West',
                        'addressLocality' => 'Mumbai',
                        'postalCode' => '400050',
                        'addressCountry' => 'IN',
                    ],
                ],
                'areaServed' => [
                    '@type' => 'City',
                    'name' => 'Mumbai',
                ],
            ]),
        ];

        if (! $image) {
            $graph = collect($graph)
                ->reject(fn (array $item): bool => ($item['@type'] ?? null) === 'ImageObject')
                ->values()
                ->all();
        }

        $faqItems = collect($this->faqs ?: [])
            ->filter(fn (array $faq): bool => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $this->plainText($faq['answer']),
                ],
            ])
            ->values()
            ->all();

        if ($faqItems !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => "{$canonical}#faq-schema",
                'mainEntity' => $faqItems,
            ];
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '';
    }

    private function absoluteUrl(?string $value): string
    {
        $clean = trim((string) $value);

        if ($clean === '') {
            return '';
        }

        if (Str::startsWith($clean, ['http://', 'https://'])) {
            return $clean;
        }

        return url('/'.ltrim($clean, '/'));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function compactSchema(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->compactSchema($value);
            }

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $clean[$key] = $value;
        }

        return $clean;
    }

    private function plainText(?string $value): string
    {
        return trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $value)));
    }
}
