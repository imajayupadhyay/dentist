<?php

namespace App\Models;

use App\Support\RichTextSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AboutPage extends Model
{
    public const KEY = 'about';

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
        'masthead_eyebrow',
        'masthead_heading',
        'masthead_heading_accent',
        'masthead_heading_suffix',
        'masthead_lede',
        'masthead_meta',
        'masthead_primary_label',
        'masthead_primary_href',
        'masthead_secondary_label',
        'masthead_secondary_href',
        'masthead_lead_image',
        'masthead_lead_image_alt',
        'masthead_inset_image',
        'masthead_inset_image_alt',
        'masthead_proof_stars',
        'masthead_proof_rating',
        'masthead_proof_text',
        'figures',
        'note_eyebrow',
        'note_image',
        'note_image_alt',
        'note_quote',
        'note_body',
        'note_signature',
        'note_name',
        'note_role',
        'values_eyebrow',
        'values_heading',
        'values_heading_accent',
        'values_heading_suffix',
        'values_lede',
        'values_items',
        'team_eyebrow',
        'team_heading',
        'team_heading_accent',
        'team_heading_suffix',
        'team_lede',
        'team_image',
        'team_image_alt',
        'team_caption',
        'clinicians',
        'team_chips',
        'cta_heading',
        'cta_heading_accent',
        'cta_heading_suffix',
        'cta_body',
        'cta_primary_label',
        'cta_primary_href',
        'cta_secondary_label',
        'cta_secondary_href',
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
            'masthead_meta' => 'array',
            'masthead_proof_stars' => 'integer',
            'figures' => 'array',
            'values_items' => 'array',
            'clinicians' => 'array',
            'team_chips' => 'array',
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
            'masthead_eyebrow' => 'About the clinic',
            'masthead_heading' => 'Sixteen years of ',
            'masthead_heading_accent' => 'unhurried',
            'masthead_heading_suffix' => ' dentistry.',
            'masthead_lede' => 'We opened on Linking Road in 2009 with one rule that has not changed since: nobody leaves this chair unsure about what was done, why it was done, or what it cost.',
            'masthead_meta' => [
                ['text' => 'Bandra West, Mumbai'],
                ['text' => 'Established 2009'],
                ['text' => 'Five clinicians'],
            ],
            'masthead_primary_label' => 'Book an appointment',
            'masthead_primary_href' => '/#book',
            'masthead_secondary_label' => 'Meet the team',
            'masthead_secondary_href' => '#team',
            'masthead_lead_image' => '/assets/clinic-wide.jpg',
            'masthead_lead_image_alt' => "A dentist at work in one of the clinic's treatment rooms",
            'masthead_inset_image' => '/assets/clinic-suite.jpg',
            'masthead_inset_image_alt' => 'A treatment chair and overhead light in the clinic',
            'masthead_proof_stars' => 5,
            'masthead_proof_rating' => '4.9 out of 5',
            'masthead_proof_text' => '860 Google reviews',
            'figures' => [
                ['count' => '16', 'decimals' => '', 'suffix' => '', 'prefix' => '', 'value' => '16', 'label' => 'Years in practice'],
                ['count' => '12400', 'decimals' => '', 'suffix' => '+', 'prefix' => '', 'value' => '12,400+', 'label' => 'Treatments done'],
                ['count' => '4.9', 'decimals' => '1', 'suffix' => '★', 'prefix' => '', 'value' => '4.9★', 'label' => '860 reviews'],
                ['count' => '90', 'decimals' => '', 'suffix' => ' min', 'prefix' => '', 'value' => '90 min', 'label' => 'Per appointment'],
            ],
            'note_eyebrow' => "Founder's note",
            'note_image' => '/assets/doctor-portrait.jpg',
            'note_image_alt' => 'Dr. Pushpa Patel in the clinic corridor',
            'note_quote' => 'I trained to fix teeth. Sixteen years in, the harder part is making someone comfortable enough to let you.',
            'note_body' => 'Almost everyone who walks in here has a story about a dentist who was in a hurry. So the practice was built around the opposite of that — ninety-minute appointments, one clinician from the first scan to the final polish, and a written plan with a price on it before anything begins.',
            'note_signature' => 'P. Patel',
            'note_name' => 'Dr. Pushpa Patel',
            'note_role' => 'BDS, MDS (Prosthodontics) · Founder & Principal Dentist',
            'values_eyebrow' => 'How we work',
            'values_heading' => 'Four things we ',
            'values_heading_accent' => 'never',
            'values_heading_suffix' => ' rush.',
            'values_lede' => 'None of this is complicated. It is simply what a dental visit looks like when the diary is built around the patient rather than the other way round.',
            'values_items' => [
                [
                    'num' => '01',
                    'title' => 'Unhurried by design',
                    'copy' => 'Ninety minutes per appointment. Long enough to explain everything, and long enough to stop if you need a minute.',
                ],
                [
                    'num' => '02',
                    'title' => 'One clinician, start to finish',
                    'copy' => 'The dentist who plans your treatment is the one who carries it out. No handovers halfway through a course.',
                ],
                [
                    'num' => '03',
                    'title' => 'A plan before a drill',
                    'copy' => 'Every course of treatment starts as one written page: what, why, how long it takes and exactly what it costs.',
                ],
                [
                    'num' => '04',
                    'title' => 'Comfort is a technique',
                    'copy' => 'Topical before the needle, computer-controlled anaesthesia, and a raised hand that stops everything at once.',
                ],
            ],
            'team_eyebrow' => 'The team',
            'team_heading' => 'Small enough to know ',
            'team_heading_accent' => 'your name.',
            'team_heading_suffix' => '',
            'team_lede' => 'Five clinicians and a front desk that remembers what you were anxious about last time. You will see the same faces on every visit — that is the whole point.',
            'team_image' => '/assets/team.jpg',
            'team_image_alt' => "Two of the clinic's dentists in a treatment room",
            'team_caption' => 'Turner House, Linking Road — four surgeries, one sterilisation bay, no waiting-room queue.',
            'clinicians' => [
                ['name' => 'Dr. Pushpa Patel', 'role' => 'Founder · Prosthodontics & smile design'],
                ['name' => 'Dr. Aditya Rao', 'role' => 'Oral implantology & jaw joint (TMD)'],
                ['name' => 'Dr. Sana Merchant', 'role' => 'Orthodontics & clear aligners'],
                ['name' => 'Dr. Nikhil Bhat', 'role' => 'Endodontics & root canal therapy'],
                ['name' => 'Dr. Ira Kulkarni', 'role' => 'Paediatric dentistry'],
            ],
            'team_chips' => [
                ['text' => 'Dental Council of India'],
                ['text' => 'ICOI fellowship'],
                ['text' => 'Invisalign certified'],
                ['text' => 'CBCT on site'],
                ['text' => 'Class B sterilisation'],
            ],
            'cta_heading' => 'Come and see the place ',
            'cta_heading_accent' => 'first.',
            'cta_heading_suffix' => '',
            'cta_body' => 'A first visit here is a conversation and a set of scans. Nothing is treated, and nothing is decided, until you have the plan in your hand.',
            'cta_primary_label' => 'Book an appointment',
            'cta_primary_href' => '/#book',
            'cta_secondary_label' => 'Call the clinic',
            'cta_secondary_href' => 'tel:+912226000000',
            'seo_title' => 'About us',
            'seo_description' => "About Dr. Pushpa Patel's Dental Clinic - sixteen years of unhurried, plan-first dentistry on Linking Road, Bandra West, Mumbai.",
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
            'masthead' => [
                'eyebrow' => $this->masthead_eyebrow,
                'heading' => $this->masthead_heading,
                'heading_accent' => $this->masthead_heading_accent,
                'heading_suffix' => $this->masthead_heading_suffix,
                'lede' => $this->sanitizeRichText($this->masthead_lede),
                'meta' => $this->masthead_meta ?: [],
                'primary_label' => $this->masthead_primary_label,
                'primary_href' => $this->masthead_primary_href,
                'secondary_label' => $this->masthead_secondary_label,
                'secondary_href' => $this->masthead_secondary_href,
                'lead_image' => $this->masthead_lead_image,
                'lead_image_alt' => $this->masthead_lead_image_alt,
                'inset_image' => $this->masthead_inset_image,
                'inset_image_alt' => $this->masthead_inset_image_alt,
                'proof_stars' => $this->masthead_proof_stars,
                'proof_rating' => $this->masthead_proof_rating,
                'proof_text' => $this->masthead_proof_text,
            ],
            'figures' => $this->figures ?: [],
            'note' => [
                'eyebrow' => $this->note_eyebrow,
                'image' => $this->note_image,
                'image_alt' => $this->note_image_alt,
                'quote' => $this->note_quote,
                'body' => $this->sanitizeRichText($this->note_body),
                'signature' => $this->note_signature,
                'name' => $this->note_name,
                'role' => $this->note_role,
            ],
            'values' => [
                'eyebrow' => $this->values_eyebrow,
                'heading' => $this->values_heading,
                'heading_accent' => $this->values_heading_accent,
                'heading_suffix' => $this->values_heading_suffix,
                'lede' => $this->sanitizeRichText($this->values_lede),
                'items' => $this->sanitizeRows($this->values_items ?: [], ['copy']),
            ],
            'team' => [
                'eyebrow' => $this->team_eyebrow,
                'heading' => $this->team_heading,
                'heading_accent' => $this->team_heading_accent,
                'heading_suffix' => $this->team_heading_suffix,
                'lede' => $this->sanitizeRichText($this->team_lede),
                'image' => $this->team_image,
                'image_alt' => $this->team_image_alt,
                'caption' => $this->team_caption,
                'clinicians' => $this->clinicians ?: [],
                'chips' => $this->team_chips ?: [],
            ],
            'cta' => [
                'heading' => $this->cta_heading,
                'heading_accent' => $this->cta_heading_accent,
                'heading_suffix' => $this->cta_heading_suffix,
                'body' => $this->sanitizeRichText($this->cta_body),
                'primary_label' => $this->cta_primary_label,
                'primary_href' => $this->cta_primary_href,
                'secondary_label' => $this->cta_secondary_label,
                'secondary_href' => $this->cta_secondary_href,
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
            'masthead_eyebrow' => $this->masthead_eyebrow,
            'masthead_heading' => $this->masthead_heading,
            'masthead_heading_accent' => $this->masthead_heading_accent,
            'masthead_heading_suffix' => $this->masthead_heading_suffix,
            'masthead_lede' => $this->sanitizeRichText($this->masthead_lede),
            'masthead_meta' => $this->masthead_meta ?: [],
            'masthead_primary_label' => $this->masthead_primary_label,
            'masthead_primary_href' => $this->masthead_primary_href,
            'masthead_secondary_label' => $this->masthead_secondary_label,
            'masthead_secondary_href' => $this->masthead_secondary_href,
            'masthead_lead_image' => $this->masthead_lead_image,
            'masthead_lead_image_alt' => $this->masthead_lead_image_alt,
            'masthead_inset_image' => $this->masthead_inset_image,
            'masthead_inset_image_alt' => $this->masthead_inset_image_alt,
            'masthead_proof_stars' => $this->masthead_proof_stars,
            'masthead_proof_rating' => $this->masthead_proof_rating,
            'masthead_proof_text' => $this->masthead_proof_text,
            'figures' => $this->figures ?: [],
            'note_eyebrow' => $this->note_eyebrow,
            'note_image' => $this->note_image,
            'note_image_alt' => $this->note_image_alt,
            'note_quote' => $this->note_quote,
            'note_body' => $this->sanitizeRichText($this->note_body),
            'note_signature' => $this->note_signature,
            'note_name' => $this->note_name,
            'note_role' => $this->note_role,
            'values_eyebrow' => $this->values_eyebrow,
            'values_heading' => $this->values_heading,
            'values_heading_accent' => $this->values_heading_accent,
            'values_heading_suffix' => $this->values_heading_suffix,
            'values_lede' => $this->sanitizeRichText($this->values_lede),
            'values_items' => $this->sanitizeRows($this->values_items ?: [], ['copy']),
            'team_eyebrow' => $this->team_eyebrow,
            'team_heading' => $this->team_heading,
            'team_heading_accent' => $this->team_heading_accent,
            'team_heading_suffix' => $this->team_heading_suffix,
            'team_lede' => $this->sanitizeRichText($this->team_lede),
            'team_image' => $this->team_image,
            'team_image_alt' => $this->team_image_alt,
            'team_caption' => $this->team_caption,
            'clinicians' => $this->clinicians ?: [],
            'team_chips' => $this->team_chips ?: [],
            'cta_heading' => $this->cta_heading,
            'cta_heading_accent' => $this->cta_heading_accent,
            'cta_heading_suffix' => $this->cta_heading_suffix,
            'cta_body' => $this->sanitizeRichText($this->cta_body),
            'cta_primary_label' => $this->cta_primary_label,
            'cta_primary_href' => $this->cta_primary_href,
            'cta_secondary_label' => $this->cta_secondary_label,
            'cta_secondary_href' => $this->cta_secondary_href,
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
        $title = $this->seo_title ?: 'About us';
        $description = $this->seo_description ?: $this->plainText($this->masthead_lede);
        $canonical = $this->seo_canonical_url ?: url('/about-us');
        $ogImage = $this->absoluteUrl($this->seo_og_image ?: $this->masthead_lead_image ?: $this->team_image);
        $twitterImage = $this->absoluteUrl($this->seo_twitter_image ?: $this->seo_og_image ?: $this->masthead_lead_image ?: $this->team_image);
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
                'image_alt' => $this->seo_og_image_alt ?: $this->masthead_lead_image_alt ?: $ogTitle,
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
        $canonical = $this->seo_canonical_url ?: url('/about-us');
        $title = $this->seo_title ?: 'About us';
        $description = $this->seo_description ?: $this->plainText($this->masthead_lede);
        $image = $this->absoluteUrl($this->seo_og_image ?: $this->masthead_lead_image ?: $this->team_image);
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
                '@type' => 'AboutPage',
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
                'caption' => $this->seo_og_image_alt ?: $this->masthead_lead_image_alt,
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
                        'name' => 'About us',
                        'item' => $canonical,
                    ],
                ],
            ],
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
