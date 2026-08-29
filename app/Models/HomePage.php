<?php

namespace App\Models;

use App\Support\RichTextSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomePage extends Model
{
    public const KEY = 'home';

    public const TWITTER_CARDS = [
        'summary_large_image' => 'Large image card',
        'summary' => 'Summary card',
    ];

    public const SCHEMA_TYPES = [
        'Dentist' => 'Dentist / clinic',
        'MedicalBusiness' => 'Medical business',
        'LocalBusiness' => 'Local business',
    ];

    protected $fillable = [
        'key',
        'hero_slides',
        'hero_trust_items',
        'about_eyebrow',
        'about_heading',
        'about_heading_accent',
        'about_body',
        'about_cta_label',
        'about_cta_href',
        'about_stats',
        'stories_eyebrow',
        'stories_heading',
        'stories_heading_accent',
        'stories_items',
        'contact_eyebrow',
        'contact_heading',
        'contact_heading_accent',
        'contact_map_title',
        'contact_map_src',
        'contact_form_heading',
        'contact_form_intro',
        'contact_form_treatment_options',
        'contact_form_time_options',
        'contact_form_submit_label',
        'contact_form_privacy_note',
        'contact_form_success_title',
        'contact_form_success_body',
        'seo_title',
        'seo_description',
        'seo_canonical_url',
        'seo_focus_keyword',
        'seo_secondary_keywords',
        'seo_robots_index',
        'seo_robots_follow',
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
            'hero_slides' => 'array',
            'hero_trust_items' => 'array',
            'about_stats' => 'array',
            'stories_items' => 'array',
            'contact_form_treatment_options' => 'array',
            'contact_form_time_options' => 'array',
            'seo_robots_index' => 'boolean',
            'seo_robots_follow' => 'boolean',
            'seo_enable_schema' => 'boolean',
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
            'hero_slides' => [
                [
                    'eyebrow' => 'Bandra West · Mumbai',
                    'heading' => 'Your best smile, ',
                    'heading_accent' => 'by design.',
                    'copy' => 'Digital smile design, ceramic artistry and ninety-minute appointments — so nothing is rushed, and nothing is missed.',
                    'primary_label' => 'Book appointment',
                    'primary_href' => '#book',
                    'secondary_label' => 'See treatments',
                    'secondary_href' => '#treatments',
                    'image' => '/assets/hero-smile.jpg',
                    'image_alt' => 'A smiling dental patient',
                    'dot' => 'Smile design',
                ],
                [
                    'eyebrow' => 'Comfort first',
                    'heading' => 'Dentistry that ',
                    'heading_accent' => "doesn't hurt.",
                    'copy' => 'Numbing before the needle, computer-controlled delivery, and a hand signal that stops everything the moment you raise it.',
                    'primary_label' => 'Book appointment',
                    'primary_href' => '#book',
                    'secondary_label' => 'How we work',
                    'secondary_href' => '#about',
                    'image' => '/assets/smile-closeup.jpg',
                    'image_alt' => 'A close view of a healthy smile',
                    'dot' => 'Painless care',
                ],
                [
                    'eyebrow' => 'All under one roof',
                    'heading' => 'Implants, aligners, ',
                    'heading_accent' => 'everything.',
                    'copy' => 'From same-day emergencies to full-mouth rehabilitation — planned, placed and finished in-house by the same clinician.',
                    'primary_label' => 'Book appointment',
                    'primary_href' => '#book',
                    'secondary_label' => 'Browse all',
                    'secondary_href' => '#treatments',
                    'image' => '/assets/clinic-suite.jpg',
                    'image_alt' => 'Modern dental clinic suite',
                    'dot' => 'Full service',
                ],
            ],
            'hero_trust_items' => [
                ['value' => '16', 'label' => 'Years in practice'],
                ['value' => '12,400+', 'label' => 'Treatments done'],
                ['value' => '4.9★', 'label' => '860 Google reviews'],
            ],
            'about_eyebrow' => 'About the clinic',
            'about_heading' => 'Unhurried dentistry in ',
            'about_heading_accent' => 'Bandra West.',
            'about_body' => "Sixteen years on Linking Road, built around one observation: people don't dread the dentist, they dread being rushed. Ninety-minute appointments, one clinician from first scan to final polish, and a written plan before anything begins.",
            'about_cta_label' => 'See what we do',
            'about_cta_href' => '#treatments',
            'about_stats' => [
                ['value' => '16', 'label' => 'Years in practice'],
                ['value' => '12,400+', 'label' => 'Treatments done'],
                ['value' => '4.9★', 'label' => '860 reviews'],
                ['value' => '90 min', 'label' => 'Per appointment'],
            ],
            'stories_eyebrow' => 'Patient stories',
            'stories_heading' => 'Real stories, ',
            'stories_heading_accent' => 'real smiles.',
            'stories_items' => [
                ['src' => '/assets/video/story-1.mp4', 'poster' => '/assets/portrait-warm.jpg', 'name' => 'Priya Nair', 'tag' => 'Sample'],
                ['src' => '/assets/video/story-2.mp4', 'poster' => '/assets/bw-smile.jpg', 'name' => 'Rakesh Menon', 'tag' => 'Sample'],
                ['src' => '/assets/video/story-3.mp4', 'poster' => '/assets/whitening.jpg', 'name' => 'Meera Iyer', 'tag' => 'Sample'],
                ['src' => '/assets/video/story-4.mp4', 'poster' => '/assets/hero-smile.jpg', 'name' => 'Anand Sharma', 'tag' => 'Sample'],
            ],
            'contact_eyebrow' => 'Get in touch',
            'contact_heading' => 'Book your visit, ',
            'contact_heading_accent' => 'in a minute.',
            'contact_map_title' => "Map showing Dr. Pushpa Patel's Dental Clinic, Linking Road, Bandra West, Mumbai",
            'contact_map_src' => 'https://maps.google.com/maps?q=Linking%20Road%2C%20Bandra%20West%2C%20Mumbai&z=15&output=embed',
            'contact_form_heading' => 'Request an appointment',
            'contact_form_intro' => 'Send this and the front desk will call you back the same working day. Nothing is confirmed until you have spoken to a person.',
            'contact_form_treatment_options' => [
                ['label' => 'General check-up'],
                ['label' => 'Pain or emergency'],
                ['label' => 'Dental implants'],
                ['label' => 'Invisible aligners'],
                ['label' => 'Smile design'],
                ['label' => 'Jaw joint (TMD)'],
                ['label' => "Kids' dentistry"],
            ],
            'contact_form_time_options' => [
                ['label' => 'Morning · 9:30 – 13:00'],
                ['label' => 'Afternoon · 13:00 – 17:00'],
                ['label' => 'Evening · 17:00 – 19:30'],
            ],
            'contact_form_submit_label' => 'Request a call back',
            'contact_form_privacy_note' => 'We reply the same working day. Your details are never shared.',
            'contact_form_success_title' => "Thank you — that's with the front desk.",
            'contact_form_success_body' => 'Someone will call you before the end of the day to confirm a time.',
            'seo_title' => "Dr. Pushpa Patel's Dental Clinic",
            'seo_description' => "Dr. Pushpa Patel's Dental Clinic — modern, unhurried dentistry in Bandra West, Mumbai.",
            'seo_canonical_url' => '',
            'seo_focus_keyword' => '',
            'seo_secondary_keywords' => '',
            'seo_robots_index' => true,
            'seo_robots_follow' => true,
            'seo_og_title' => '',
            'seo_og_description' => '',
            'seo_og_image' => '',
            'seo_og_image_alt' => '',
            'seo_twitter_card' => 'summary_large_image',
            'seo_twitter_title' => '',
            'seo_twitter_description' => '',
            'seo_twitter_image' => '',
            'seo_enable_schema' => true,
            'seo_schema_type' => 'Dentist',
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

    /**
     * @return array<string, mixed>
     */
    public function toPageData(): array
    {
        return [
            'hero_slides' => $this->sanitizeRows($this->hero_slides ?: [], ['copy']),
            'hero_trust_items' => $this->hero_trust_items ?: [],
            'about' => [
                'eyebrow' => $this->about_eyebrow,
                'heading' => $this->about_heading,
                'heading_accent' => $this->about_heading_accent,
                'body' => $this->sanitizeRichText($this->about_body),
                'cta_label' => $this->about_cta_label,
                'cta_href' => $this->about_cta_href,
            ],
            'about_stats' => $this->about_stats ?: [],
            'stories' => [
                'eyebrow' => $this->stories_eyebrow,
                'heading' => $this->stories_heading,
                'heading_accent' => $this->stories_heading_accent,
                'items' => $this->stories_items ?: [],
            ],
            'contact' => [
                'eyebrow' => $this->contact_eyebrow,
                'heading' => $this->contact_heading,
                'heading_accent' => $this->contact_heading_accent,
                'map_title' => $this->contact_map_title,
                'map_src' => $this->contact_map_src,
                'form' => [
                    'heading' => $this->contact_form_heading,
                    'intro' => $this->sanitizeRichText($this->contact_form_intro),
                    'treatment_options' => $this->contact_form_treatment_options ?: [],
                    'time_options' => $this->contact_form_time_options ?: [],
                    'submit_label' => $this->contact_form_submit_label,
                    'privacy_note' => $this->contact_form_privacy_note,
                    'success_title' => $this->contact_form_success_title,
                    'success_body' => $this->sanitizeRichText($this->contact_form_success_body),
                ],
            ],
            'seo_meta' => $this->toSeoMeta(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'hero_slides' => $this->sanitizeRows($this->hero_slides ?: [], ['copy']),
            'hero_trust_items' => $this->hero_trust_items ?: [],
            'about_eyebrow' => $this->about_eyebrow,
            'about_heading' => $this->about_heading,
            'about_heading_accent' => $this->about_heading_accent,
            'about_body' => $this->sanitizeRichText($this->about_body),
            'about_cta_label' => $this->about_cta_label,
            'about_cta_href' => $this->about_cta_href,
            'about_stats' => $this->about_stats ?: [],
            'stories_eyebrow' => $this->stories_eyebrow,
            'stories_heading' => $this->stories_heading,
            'stories_heading_accent' => $this->stories_heading_accent,
            'stories_items' => $this->stories_items ?: [],
            'contact_eyebrow' => $this->contact_eyebrow,
            'contact_heading' => $this->contact_heading,
            'contact_heading_accent' => $this->contact_heading_accent,
            'contact_map_title' => $this->contact_map_title,
            'contact_map_src' => $this->contact_map_src,
            'contact_form_heading' => $this->contact_form_heading,
            'contact_form_intro' => $this->sanitizeRichText($this->contact_form_intro),
            'contact_form_treatment_options' => $this->contact_form_treatment_options ?: [],
            'contact_form_time_options' => $this->contact_form_time_options ?: [],
            'contact_form_submit_label' => $this->contact_form_submit_label,
            'contact_form_privacy_note' => $this->contact_form_privacy_note,
            'contact_form_success_title' => $this->contact_form_success_title,
            'contact_form_success_body' => $this->sanitizeRichText($this->contact_form_success_body),
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_canonical_url' => $this->seo_canonical_url,
            'seo_focus_keyword' => $this->seo_focus_keyword,
            'seo_secondary_keywords' => $this->seo_secondary_keywords,
            'seo_robots_index' => $this->seo_robots_index,
            'seo_robots_follow' => $this->seo_robots_follow,
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
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSeoMeta(): array
    {
        $title = $this->seo_title ?: "Dr. Pushpa Patel's Dental Clinic";
        $description = $this->seo_description ?: $this->plainText($this->about_body);
        $canonical = $this->seo_canonical_url ?: url('/');
        $ogImage = $this->absoluteUrl($this->seo_og_image ?: $this->primaryImage());
        $twitterImage = $this->absoluteUrl($this->seo_twitter_image ?: $this->seo_og_image ?: $this->primaryImage());
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
                'type' => 'website',
                'title' => $ogTitle,
                'description' => $ogDescription,
                'url' => $canonical,
                'image' => $ogImage,
                'image_alt' => $this->seo_og_image_alt ?: $this->primaryImageAlt() ?: $ogTitle,
            ],
            'twitter' => [
                'card' => $this->seo_twitter_card ?: 'summary_large_image',
                'title' => $this->seo_twitter_title ?: $ogTitle,
                'description' => $this->seo_twitter_description ?: $ogDescription,
                'image' => $twitterImage,
            ],
            'article' => [],
            'json_ld' => $this->seo_enable_schema ? $this->jsonLd() : '',
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

    private function sanitizeRichText(?string $value): string
    {
        return app(RichTextSanitizer::class)->sanitize($value);
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @param  list<string>  $richFields
     * @return list<array<string, mixed>>
     */
    private function sanitizeRows(array $rows, array $richFields): array
    {
        return collect($rows)
            ->filter(fn (mixed $row): bool => is_array($row))
            ->map(function (array $row) use ($richFields): array {
                foreach ($richFields as $field) {
                    $row[$field] = $this->sanitizeRichText($row[$field] ?? null);
                }

                return $row;
            })
            ->values()
            ->all();
    }

    private function jsonLd(): string
    {
        $clinicUrl = rtrim(url('/'), '/');
        $canonical = $this->seo_canonical_url ?: url('/');
        $title = $this->seo_title ?: "Dr. Pushpa Patel's Dental Clinic";
        $description = $this->seo_description ?: $this->plainText($this->about_body);
        $image = $this->absoluteUrl($this->seo_og_image ?: $this->primaryImage());
        $schemaType = $this->seo_schema_type ?: 'Dentist';
        $schemaName = $this->seo_schema_name ?: "Dr. Pushpa Patel's Dental Clinic";
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
            ]),
            $this->compactSchema([
                '@type' => 'ImageObject',
                '@id' => "{$canonical}#primaryimage",
                'url' => $image,
                'caption' => $this->seo_og_image_alt ?: $this->primaryImageAlt(),
            ]),
            $this->compactSchema([
                '@type' => $schemaType,
                '@id' => "{$clinicUrl}#clinic",
                'name' => $schemaName,
                'description' => $schemaDescription,
                'url' => "{$clinicUrl}/",
                'image' => $image,
                'telephone' => '+912226000000',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '2nd Floor, Turner House, Linking Road, Bandra West',
                    'addressLocality' => 'Mumbai',
                    'postalCode' => '400050',
                    'addressCountry' => 'IN',
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

        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '';
    }

    private function primaryImage(): string
    {
        $firstSlide = collect($this->hero_slides ?: [])
            ->first(fn (mixed $slide): bool => is_array($slide) && filled($slide['image'] ?? null));

        return is_array($firstSlide) ? (string) $firstSlide['image'] : '';
    }

    private function primaryImageAlt(): string
    {
        $firstSlide = collect($this->hero_slides ?: [])
            ->first(fn (mixed $slide): bool => is_array($slide) && filled($slide['image_alt'] ?? null));

        return is_array($firstSlide) ? (string) $firstSlide['image_alt'] : '';
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
