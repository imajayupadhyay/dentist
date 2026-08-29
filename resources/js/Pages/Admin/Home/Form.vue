<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';
import RichTextEditor from '../Components/RichTextEditor.vue';

const props = defineProps({
    homePage: {
        type: Object,
        required: true,
    },
    seoOptions: {
        type: Object,
        default: () => ({
            twitter_cards: [],
            schema_types: [],
        }),
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const activeTab = ref('content');

const tabs = [
    { id: 'seo', label: 'SEO' },
    { id: 'content', label: 'Content' },
];

const tabFields = {
    seo: [
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
        'seo_og_image_file',
        'seo_og_image_alt',
        'seo_twitter_card',
        'seo_twitter_title',
        'seo_twitter_description',
        'seo_twitter_image',
        'seo_twitter_image_file',
        'seo_enable_schema',
        'seo_schema_type',
        'seo_schema_name',
        'seo_schema_description',
    ],
    content: [
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
    ],
};

const form = useForm({
    ...props.homePage,
    seo_og_image_file: null,
    seo_twitter_image_file: null,
});

ensureRepeats();

const twitterCards = computed(() => props.seoOptions.twitter_cards?.length
    ? props.seoOptions.twitter_cards
    : [
        { value: 'summary_large_image', label: 'Large image card' },
        { value: 'summary', label: 'Summary card' },
    ]);

const schemaTypes = computed(() => props.seoOptions.schema_types?.length
    ? props.seoOptions.schema_types
    : [
        { value: 'Dentist', label: 'Dentist / clinic' },
        { value: 'MedicalBusiness', label: 'Medical business' },
        { value: 'LocalBusiness', label: 'Local business' },
    ]);

const firstHeroImage = computed(() => form.hero_slides.find((slide) => slide.image)?.image || '');
const seoPreviewTitle = computed(() => form.seo_title || 'Home page title');
const seoPreviewDescription = computed(() => form.seo_description || textOnly(form.about_body) || 'The search description will appear here once added.');
const seoPreviewUrl = computed(() => form.seo_canonical_url || '/');
const robotsPreview = computed(() => [
    form.seo_robots_index ? 'index' : 'noindex',
    form.seo_robots_follow ? 'follow' : 'nofollow',
    'max-image-preview:large',
    'max-snippet:-1',
    'max-video-preview:-1',
].join(','));
const keywordPreview = computed(() => [form.seo_focus_keyword, form.seo_secondary_keywords].filter(Boolean).join(', '));
const openGraphPreviewTitle = computed(() => form.seo_og_title || seoPreviewTitle.value);
const openGraphPreviewDescription = computed(() => form.seo_og_description || seoPreviewDescription.value);
const openGraphPreviewImage = computed(() => form.seo_og_image || firstHeroImage.value);
const twitterPreviewTitle = computed(() => form.seo_twitter_title || openGraphPreviewTitle.value);
const twitterPreviewDescription = computed(() => form.seo_twitter_description || openGraphPreviewDescription.value);
const twitterPreviewImage = computed(() => form.seo_twitter_image || openGraphPreviewImage.value);
const schemaPreviewName = computed(() => form.seo_schema_name || 'Dr. Pushpa Patel Dental Clinic');
const schemaPreviewDescription = computed(() => form.seo_schema_description || seoPreviewDescription.value);

watch(
    () => Object.keys(form.errors).join('|'),
    () => {
        const tab = tabs.find((item) => tabHasError(item.id));

        if (tab) {
            activeTab.value = tab.id;
        }
    },
);

function ensureRepeats() {
    if (! Array.isArray(form.hero_slides) || form.hero_slides.length === 0) {
        form.hero_slides = [blankHeroSlide()];
    }
    if (! Array.isArray(form.hero_trust_items) || form.hero_trust_items.length === 0) {
        form.hero_trust_items = [{ value: '', label: '' }];
    }
    if (! Array.isArray(form.about_stats) || form.about_stats.length === 0) {
        form.about_stats = [{ value: '', label: '' }];
    }
    if (! Array.isArray(form.stories_items) || form.stories_items.length === 0) {
        form.stories_items = [blankStory()];
    }
    if (! Array.isArray(form.contact_form_treatment_options) || form.contact_form_treatment_options.length === 0) {
        form.contact_form_treatment_options = [blankContactOption()];
    }
    if (! Array.isArray(form.contact_form_time_options) || form.contact_form_time_options.length === 0) {
        form.contact_form_time_options = [blankContactOption()];
    }
}

function blankHeroSlide() {
    return {
        eyebrow: '',
        heading: '',
        heading_accent: '',
        copy: '',
        primary_label: 'Book appointment',
        primary_href: '#book',
        secondary_label: '',
        secondary_href: '',
        image: '',
        image_alt: '',
        dot: '',
        image_file: null,
    };
}

function blankStory() {
    return {
        src: '',
        poster: '',
        name: '',
        tag: 'Sample',
        video_file: null,
        poster_file: null,
    };
}

function blankContactOption() {
    return {
        label: '',
    };
}

function addRow(key, row) {
    form[key].push({ ...row });
}

function removeRow(key, index, row) {
    form[key].splice(index, 1);

    if (form[key].length === 0) {
        addRow(key, row);
    }
}

function setFile(field, event) {
    form[field] = event.target.files?.[0] ?? null;
}

function setRowFile(row, field, event) {
    row[field] = event.target.files?.[0] ?? null;
}

function submit() {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/admin/home', {
            forceFormData: true,
            preserveScroll: true,
        });
}

function errorFor(field) {
    return form.errors[field];
}

function tabHasError(tab) {
    const fields = tabFields[tab] ?? [];
    const errors = Object.keys(form.errors);

    return errors.some((error) => fields.some((field) => error === field || error.startsWith(`${field}.`)));
}

function textOnly(value) {
    return String(value || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}
</script>

<template>
    <Head title="Admin Home">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Home">
        <section class="admin-treatment-page admin-home-page">
            <div class="admin-treatment-toolbar admin-home-toolbar">
                <div>
                    <span class="admin-kicker">Page module</span>
                    <h2>Home</h2>
                    <p>Manage homepage SEO, hero, intro, patient stories, contact map, and contact form content. Treatments, reviews, header and footer are managed separately.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <a class="admin-action-secondary" href="/" target="_blank" rel="noopener">View live</a>
                    <Link class="admin-action-secondary" href="/admin/dashboard">Dashboard</Link>
                </div>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div v-if="Object.keys(form.errors).length" class="admin-error-summary">
                <b>Please fix the highlighted fields.</b>
                <span>{{ Object.keys(form.errors).length }} validation issue(s) found.</span>
            </div>

            <div class="admin-editor-tabs" role="tablist" aria-label="Home editor sections">
                <button
                    v-for="tab in tabs"
                    :id="`admin-tab-${tab.id}`"
                    :key="tab.id"
                    type="button"
                    role="tab"
                    :class="{ active: activeTab === tab.id, invalid: tabHasError(tab.id) }"
                    :aria-selected="activeTab === tab.id ? 'true' : 'false'"
                    :aria-controls="`admin-panel-${tab.id}`"
                    @click="activeTab = tab.id"
                >
                    {{ tab.label }}
                    <span v-if="tabHasError(tab.id)">Needs attention</span>
                </button>
            </div>

            <form class="admin-treatment-form admin-home-form" @submit.prevent="submit">
                <section
                    v-show="activeTab === 'seo'"
                    id="admin-panel-seo"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-seo"
                >
                    <div class="admin-panel-head">
                        <span>01</span>
                        <div>
                            <h3>SEO</h3>
                            <p>This controls the homepage search, social sharing and structured metadata.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Search basics</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Canonical URL</span>
                                <input v-model="form.seo_canonical_url" type="url" placeholder="https://example.com/">
                                <small v-if="errorFor('seo_canonical_url')">{{ errorFor('seo_canonical_url') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>SEO title</span>
                                <input v-model="form.seo_title" type="text" maxlength="180">
                                <small v-if="errorFor('seo_title')">{{ errorFor('seo_title') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>SEO description</span>
                                <textarea v-model="form.seo_description" rows="4" maxlength="300"></textarea>
                                <small v-if="errorFor('seo_description')">{{ errorFor('seo_description') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Focus keyword</span>
                                <input v-model="form.seo_focus_keyword" type="text" maxlength="120">
                                <small v-if="errorFor('seo_focus_keyword')">{{ errorFor('seo_focus_keyword') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Secondary keywords</span>
                                <input v-model="form.seo_secondary_keywords" type="text" maxlength="500" placeholder="keyword one, keyword two">
                                <small v-if="errorFor('seo_secondary_keywords')">{{ errorFor('seo_secondary_keywords') }}</small>
                            </label>

                            <div v-if="keywordPreview" class="admin-seo-preview wide">
                                <span>Keyword preview</span>
                                <p>{{ keywordPreview }}</p>
                            </div>

                            <div class="admin-seo-preview wide">
                                <span>Search preview</span>
                                <b>{{ seoPreviewTitle }}</b>
                                <small>{{ seoPreviewUrl }}</small>
                                <p>{{ seoPreviewDescription }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Crawl controls</h4>
                        <div class="admin-form-grid">
                            <label class="admin-check admin-field-check">
                                <input v-model="form.seo_robots_index" type="checkbox">
                                <span>Allow search indexing</span>
                            </label>

                            <label class="admin-check admin-field-check">
                                <input v-model="form.seo_robots_follow" type="checkbox">
                                <span>Allow link following</span>
                            </label>

                            <div class="admin-seo-preview wide">
                                <span>Robots meta</span>
                                <p>{{ robotsPreview }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Social sharing</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Open Graph title</span>
                                <input v-model="form.seo_og_title" type="text" maxlength="180">
                                <small v-if="errorFor('seo_og_title')">{{ errorFor('seo_og_title') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Open Graph image path</span>
                                <input v-model="form.seo_og_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('seo_og_image')">{{ errorFor('seo_og_image') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Open Graph description</span>
                                <textarea v-model="form.seo_og_description" rows="3" maxlength="300"></textarea>
                                <small v-if="errorFor('seo_og_description')">{{ errorFor('seo_og_description') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload Open Graph image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('seo_og_image_file', $event)">
                                <small v-if="errorFor('seo_og_image_file')">{{ errorFor('seo_og_image_file') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Open Graph image alt text</span>
                                <input v-model="form.seo_og_image_alt" type="text" maxlength="255">
                                <small v-if="errorFor('seo_og_image_alt')">{{ errorFor('seo_og_image_alt') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Twitter card</span>
                                <select v-model="form.seo_twitter_card">
                                    <option v-for="card in twitterCards" :key="card.value" :value="card.value">{{ card.label }}</option>
                                </select>
                                <small v-if="errorFor('seo_twitter_card')">{{ errorFor('seo_twitter_card') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Twitter title</span>
                                <input v-model="form.seo_twitter_title" type="text" maxlength="180">
                                <small v-if="errorFor('seo_twitter_title')">{{ errorFor('seo_twitter_title') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Twitter description</span>
                                <textarea v-model="form.seo_twitter_description" rows="3" maxlength="300"></textarea>
                                <small v-if="errorFor('seo_twitter_description')">{{ errorFor('seo_twitter_description') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Twitter image path</span>
                                <input v-model="form.seo_twitter_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('seo_twitter_image')">{{ errorFor('seo_twitter_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload Twitter image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('seo_twitter_image_file', $event)">
                                <small v-if="errorFor('seo_twitter_image_file')">{{ errorFor('seo_twitter_image_file') }}</small>
                            </label>

                            <div class="admin-social-preview wide">
                                <div v-if="openGraphPreviewImage" class="admin-social-preview-media">
                                    <img :src="openGraphPreviewImage" :alt="form.seo_og_image_alt || ''">
                                </div>
                                <div>
                                    <span>Social preview</span>
                                    <b>{{ openGraphPreviewTitle }}</b>
                                    <p>{{ openGraphPreviewDescription }}</p>
                                    <small>{{ twitterPreviewTitle }} · {{ twitterPreviewDescription }} · {{ twitterPreviewImage || 'No Twitter image selected' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Structured data</h4>
                        <div class="admin-form-grid">
                            <label class="admin-check admin-field-check">
                                <input v-model="form.seo_enable_schema" type="checkbox">
                                <span>Enable JSON-LD schema</span>
                            </label>

                            <label class="admin-field">
                                <span>Schema type</span>
                                <select v-model="form.seo_schema_type">
                                    <option v-for="type in schemaTypes" :key="type.value" :value="type.value">{{ type.label }}</option>
                                </select>
                                <small v-if="errorFor('seo_schema_type')">{{ errorFor('seo_schema_type') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Schema name</span>
                                <input v-model="form.seo_schema_name" type="text" maxlength="180">
                                <small v-if="errorFor('seo_schema_name')">{{ errorFor('seo_schema_name') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Schema description</span>
                                <textarea v-model="form.seo_schema_description" rows="3" maxlength="500"></textarea>
                                <small v-if="errorFor('seo_schema_description')">{{ errorFor('seo_schema_description') }}</small>
                            </label>

                            <div class="admin-seo-preview wide">
                                <span>Schema preview</span>
                                <b>{{ schemaPreviewName }}</b>
                                <small>{{ form.seo_schema_type || 'Dentist' }}</small>
                                <p>{{ schemaPreviewDescription }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'content'"
                    id="admin-panel-content"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-content"
                >
                    <div class="admin-panel-head">
                        <span>02</span>
                        <div>
                            <h3>Content</h3>
                            <p>This controls the homepage sections that are not treatments, reviews, header, or footer.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <div class="admin-repeat-head">
                            <h4>Hero carousel</h4>
                            <button type="button" @click="addRow('hero_slides', blankHeroSlide())">Add slide</button>
                        </div>

                        <div class="admin-home-card-list">
                            <article v-for="(slide, index) in form.hero_slides" :key="index" class="admin-home-card">
                                <div class="admin-repeat-head">
                                    <h5>Slide {{ index + 1 }}</h5>
                                    <button type="button" @click="removeRow('hero_slides', index, blankHeroSlide())">Remove</button>
                                </div>

                                <div class="admin-form-grid">
                                    <label class="admin-field">
                                        <span>Eyebrow</span>
                                        <input v-model="slide.eyebrow" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.eyebrow`)">{{ errorFor(`hero_slides.${index}.eyebrow`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Dot label</span>
                                        <input v-model="slide.dot" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.dot`)">{{ errorFor(`hero_slides.${index}.dot`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Heading</span>
                                        <input v-model="slide.heading" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.heading`)">{{ errorFor(`hero_slides.${index}.heading`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Highlighted heading text</span>
                                        <input v-model="slide.heading_accent" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.heading_accent`)">{{ errorFor(`hero_slides.${index}.heading_accent`) }}</small>
                                    </label>

                                    <RichTextEditor
                                        v-model="slide.copy"
                                        class="admin-field wide"
                                        label="Slide copy"
                                        :error="errorFor(`hero_slides.${index}.copy`)"
                                    />

                                    <label class="admin-field">
                                        <span>Primary button label</span>
                                        <input v-model="slide.primary_label" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.primary_label`)">{{ errorFor(`hero_slides.${index}.primary_label`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Primary button href</span>
                                        <input v-model="slide.primary_href" type="text" placeholder="#book">
                                        <small v-if="errorFor(`hero_slides.${index}.primary_href`)">{{ errorFor(`hero_slides.${index}.primary_href`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Secondary button label</span>
                                        <input v-model="slide.secondary_label" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.secondary_label`)">{{ errorFor(`hero_slides.${index}.secondary_label`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Secondary button href</span>
                                        <input v-model="slide.secondary_href" type="text" placeholder="#treatments">
                                        <small v-if="errorFor(`hero_slides.${index}.secondary_href`)">{{ errorFor(`hero_slides.${index}.secondary_href`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Image path</span>
                                        <input v-model="slide.image" type="text" placeholder="/assets/example.jpg">
                                        <small v-if="errorFor(`hero_slides.${index}.image`)">{{ errorFor(`hero_slides.${index}.image`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Or upload image</span>
                                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="setRowFile(slide, 'image_file', $event)">
                                        <small v-if="errorFor(`hero_slides.${index}.image_file`)">{{ errorFor(`hero_slides.${index}.image_file`) }}</small>
                                    </label>

                                    <label class="admin-field wide">
                                        <span>Image alt text</span>
                                        <input v-model="slide.image_alt" type="text">
                                        <small v-if="errorFor(`hero_slides.${index}.image_alt`)">{{ errorFor(`hero_slides.${index}.image_alt`) }}</small>
                                    </label>
                                </div>
                            </article>
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Hero trust metrics</h5>
                                <button type="button" @click="addRow('hero_trust_items', { value: '', label: '' })">Add metric</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(item, index) in form.hero_trust_items" :key="index" class="admin-repeat-row two">
                                    <label class="admin-field">
                                        <span>Value</span>
                                        <input v-model="item.value" type="text">
                                        <small v-if="errorFor(`hero_trust_items.${index}.value`)">{{ errorFor(`hero_trust_items.${index}.value`) }}</small>
                                    </label>
                                    <label class="admin-field">
                                        <span>Label</span>
                                        <input v-model="item.label" type="text">
                                        <small v-if="errorFor(`hero_trust_items.${index}.label`)">{{ errorFor(`hero_trust_items.${index}.label`) }}</small>
                                    </label>
                                    <button type="button" @click="removeRow('hero_trust_items', index, { value: '', label: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>About intro</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.about_eyebrow" type="text">
                                <small v-if="errorFor('about_eyebrow')">{{ errorFor('about_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>CTA label</span>
                                <input v-model="form.about_cta_label" type="text">
                                <small v-if="errorFor('about_cta_label')">{{ errorFor('about_cta_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.about_heading" type="text">
                                <small v-if="errorFor('about_heading')">{{ errorFor('about_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.about_heading_accent" type="text">
                                <small v-if="errorFor('about_heading_accent')">{{ errorFor('about_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>CTA href</span>
                                <input v-model="form.about_cta_href" type="text" placeholder="#treatments">
                                <small v-if="errorFor('about_cta_href')">{{ errorFor('about_cta_href') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.about_body"
                                class="admin-field wide"
                                label="Intro body"
                                min-height="180px"
                                :error="errorFor('about_body')"
                            />
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Intro stats</h5>
                                <button type="button" @click="addRow('about_stats', { value: '', label: '' })">Add stat</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(stat, index) in form.about_stats" :key="index" class="admin-repeat-row two">
                                    <label class="admin-field">
                                        <span>Value</span>
                                        <input v-model="stat.value" type="text">
                                        <small v-if="errorFor(`about_stats.${index}.value`)">{{ errorFor(`about_stats.${index}.value`) }}</small>
                                    </label>
                                    <label class="admin-field">
                                        <span>Label</span>
                                        <input v-model="stat.label" type="text">
                                        <small v-if="errorFor(`about_stats.${index}.label`)">{{ errorFor(`about_stats.${index}.label`) }}</small>
                                    </label>
                                    <button type="button" @click="removeRow('about_stats', index, { value: '', label: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <div class="admin-repeat-head">
                            <h4>Patient stories</h4>
                            <button type="button" @click="addRow('stories_items', blankStory())">Add story</button>
                        </div>

                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.stories_eyebrow" type="text">
                                <small v-if="errorFor('stories_eyebrow')">{{ errorFor('stories_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.stories_heading" type="text">
                                <small v-if="errorFor('stories_heading')">{{ errorFor('stories_heading') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Highlighted heading text</span>
                                <input v-model="form.stories_heading_accent" type="text">
                                <small v-if="errorFor('stories_heading_accent')">{{ errorFor('stories_heading_accent') }}</small>
                            </label>
                        </div>

                        <div class="admin-home-card-list">
                            <article v-for="(story, index) in form.stories_items" :key="index" class="admin-home-card">
                                <div class="admin-repeat-head">
                                    <h5>Story {{ index + 1 }}</h5>
                                    <button type="button" @click="removeRow('stories_items', index, blankStory())">Remove</button>
                                </div>

                                <div class="admin-form-grid">
                                    <label class="admin-field">
                                        <span>Patient name</span>
                                        <input v-model="story.name" type="text">
                                        <small v-if="errorFor(`stories_items.${index}.name`)">{{ errorFor(`stories_items.${index}.name`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Chip label</span>
                                        <input v-model="story.tag" type="text">
                                        <small v-if="errorFor(`stories_items.${index}.tag`)">{{ errorFor(`stories_items.${index}.tag`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Video path</span>
                                        <input v-model="story.src" type="text" placeholder="/assets/video/example.mp4">
                                        <small v-if="errorFor(`stories_items.${index}.src`)">{{ errorFor(`stories_items.${index}.src`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Or upload video</span>
                                        <input type="file" accept="video/mp4,video/webm,video/ogg" @change="setRowFile(story, 'video_file', $event)">
                                        <small v-if="errorFor(`stories_items.${index}.video_file`)">{{ errorFor(`stories_items.${index}.video_file`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Poster image path</span>
                                        <input v-model="story.poster" type="text" placeholder="/assets/example.jpg">
                                        <small v-if="errorFor(`stories_items.${index}.poster`)">{{ errorFor(`stories_items.${index}.poster`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Or upload poster image</span>
                                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="setRowFile(story, 'poster_file', $event)">
                                        <small v-if="errorFor(`stories_items.${index}.poster_file`)">{{ errorFor(`stories_items.${index}.poster_file`) }}</small>
                                    </label>

                                    <div v-if="story.poster" class="admin-home-media-preview wide">
                                        <img :src="story.poster" :alt="story.name || ''">
                                        <span>{{ story.src || 'No video path selected' }}</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Contact heading, map and form</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.contact_eyebrow" type="text">
                                <small v-if="errorFor('contact_eyebrow')">{{ errorFor('contact_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.contact_heading" type="text">
                                <small v-if="errorFor('contact_heading')">{{ errorFor('contact_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.contact_heading_accent" type="text">
                                <small v-if="errorFor('contact_heading_accent')">{{ errorFor('contact_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Map title</span>
                                <input v-model="form.contact_map_title" type="text">
                                <small v-if="errorFor('contact_map_title')">{{ errorFor('contact_map_title') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Map embed URL</span>
                                <input v-model="form.contact_map_src" type="text" placeholder="https://maps.google.com/maps?...">
                                <small v-if="errorFor('contact_map_src')">{{ errorFor('contact_map_src') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Form heading</span>
                                <input v-model="form.contact_form_heading" type="text">
                                <small v-if="errorFor('contact_form_heading')">{{ errorFor('contact_form_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Submit button label</span>
                                <input v-model="form.contact_form_submit_label" type="text">
                                <small v-if="errorFor('contact_form_submit_label')">{{ errorFor('contact_form_submit_label') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.contact_form_intro"
                                class="admin-field wide"
                                label="Form intro"
                                min-height="140px"
                                :error="errorFor('contact_form_intro')"
                            />

                            <label class="admin-field">
                                <span>Success title</span>
                                <input v-model="form.contact_form_success_title" type="text">
                                <small v-if="errorFor('contact_form_success_title')">{{ errorFor('contact_form_success_title') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Privacy note</span>
                                <textarea v-model="form.contact_form_privacy_note" rows="3"></textarea>
                                <small v-if="errorFor('contact_form_privacy_note')">{{ errorFor('contact_form_privacy_note') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.contact_form_success_body"
                                class="admin-field wide"
                                label="Success body"
                                min-height="120px"
                                :error="errorFor('contact_form_success_body')"
                            />
                        </div>

                        <div class="admin-repeat-split">
                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Treatment dropdown options</h5>
                                    <button type="button" @click="addRow('contact_form_treatment_options', blankContactOption())">Add option</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(option, index) in form.contact_form_treatment_options" :key="index" class="admin-repeat-row">
                                        <label class="admin-field">
                                            <span>Option label</span>
                                            <input v-model="option.label" type="text">
                                            <small v-if="errorFor(`contact_form_treatment_options.${index}.label`)">{{ errorFor(`contact_form_treatment_options.${index}.label`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('contact_form_treatment_options', index, blankContactOption())">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Preferred time options</h5>
                                    <button type="button" @click="addRow('contact_form_time_options', blankContactOption())">Add option</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(option, index) in form.contact_form_time_options" :key="index" class="admin-repeat-row">
                                        <label class="admin-field">
                                            <span>Option label</span>
                                            <input v-model="option.label" type="text">
                                            <small v-if="errorFor(`contact_form_time_options.${index}.label`)">{{ errorFor(`contact_form_time_options.${index}.label`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('contact_form_time_options', index, blankContactOption())">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="admin-form-actions">
                    <Link class="admin-action-secondary" href="/admin/dashboard">Cancel</Link>
                    <button class="admin-action-primary" type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save home page' }}
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </form>
        </section>
    </AdminShell>
</template>
