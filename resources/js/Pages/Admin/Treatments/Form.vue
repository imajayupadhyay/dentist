<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';
import RichTextEditor from '../Components/RichTextEditor.vue';

const props = defineProps({
    mode: {
        type: String,
        required: true,
    },
    treatment: {
        type: Object,
        required: true,
    },
    tones: {
        type: Array,
        default: () => [],
    },
    seoOptions: {
        type: Object,
        default: () => ({
            twitter_cards: [],
            schema_types: [],
        }),
    },
});

const isEdit = computed(() => props.mode === 'edit');
const pageTitle = computed(() => isEdit.value ? `Edit ${props.treatment.home_title}` : 'Add treatment');
const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const activeTab = ref('home');

const tabs = [
    { id: 'seo', label: 'SEO' },
    { id: 'home', label: 'Home' },
    { id: 'content', label: 'Content' },
];

const tabFields = {
    seo: [
        'slug',
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
    home: [
        'sort_order',
        'tone',
        'is_active',
        'home_title',
        'home_subtitle',
        'home_description',
        'home_image',
        'home_image_file',
        'home_image_alt',
        'home_icon_svg',
    ],
    content: [
        'category',
        'title',
        'title_accent',
        'tagline',
        'summary',
        'hero_image',
        'hero_image_file',
        'hero_image_alt',
        'facts',
        'overview_eyebrow',
        'overview_heading',
        'overview_heading_accent',
        'overview_lede',
        'overview_body',
        'overview_image',
        'overview_image_file',
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
    ],
};

const form = useForm({
    ...props.treatment,
    home_image_file: null,
    hero_image_file: null,
    overview_image_file: null,
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
        { value: 'MedicalProcedure', label: 'Medical procedure' },
        { value: 'MedicalTherapy', label: 'Medical therapy' },
        { value: 'Service', label: 'Service' },
    ]);

const seoPreviewTitle = computed(() => form.seo_title || form.title || form.home_title || 'Treatment page title');
const seoPreviewDescription = computed(() => form.seo_description || textOnly(form.summary) || 'The search description will appear here once added.');
const seoPreviewUrl = computed(() => form.seo_canonical_url || `/treatments/${form.slug || 'treatment-slug'}`);
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
const openGraphPreviewImage = computed(() => form.seo_og_image || form.hero_image || form.home_image || '');
const twitterPreviewTitle = computed(() => form.seo_twitter_title || openGraphPreviewTitle.value);
const twitterPreviewDescription = computed(() => form.seo_twitter_description || openGraphPreviewDescription.value);
const twitterPreviewImage = computed(() => form.seo_twitter_image || openGraphPreviewImage.value);
const schemaPreviewName = computed(() => form.seo_schema_name || form.title || form.home_title || 'Treatment service');
const schemaPreviewDescription = computed(() => form.seo_schema_description || seoPreviewDescription.value);

watch(
    () => form.title || form.home_title,
    (value) => {
        if (! isEdit.value && ! form.slug) {
            form.slug = slugify(value);
        }
    },
);

watch(
    () => Object.keys(form.errors).join('|'),
    () => {
        const tab = tabs.find((item) => tabHasError(item.id));

        if (tab) {
            activeTab.value = tab.id;
        }
    },
);

function slugify(value) {
    return String(value || '')
        .toLowerCase()
        .replace(/&/g, 'and')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
}

function textOnly(value) {
    return String(value || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

function ensureRepeats() {
    if (! Array.isArray(form.facts) || form.facts.length === 0) {
        form.facts = [{ label: '', value: '' }];
    }
    if (! Array.isArray(form.suitable_for) || form.suitable_for.length === 0) {
        form.suitable_for = [{ text: '' }];
    }
    if (! Array.isArray(form.not_suitable) || form.not_suitable.length === 0) {
        form.not_suitable = [{ text: '' }];
    }
    if (! Array.isArray(form.steps) || form.steps.length === 0) {
        form.steps = [{ title: '', duration: '', body: '' }];
    }
    if (! Array.isArray(form.faqs) || form.faqs.length === 0) {
        form.faqs = [{ question: '', answer: '' }];
    }
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

function submit() {
    const url = isEdit.value ? `/admin/treatments/${props.treatment.slug}` : '/admin/treatments';

    form
        .transform((data) => ({
            ...data,
            _method: isEdit.value ? 'put' : 'post',
        }))
        .post(url, {
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
</script>

<template>
    <Head :title="pageTitle">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell :title="pageTitle">
        <section class="admin-treatment-page">
            <div class="admin-treatment-toolbar">
                <div>
                    <span class="admin-kicker">Treatment editor</span>
                    <h2>{{ pageTitle }}</h2>
                    <p>Fill the homepage band first, then the treatment landing page content.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <a
                        v-if="isEdit"
                        class="admin-action-secondary"
                        :href="`/treatments/${props.treatment.slug}`"
                        target="_blank"
                        rel="noopener"
                    >View live</a>
                    <Link class="admin-action-secondary" href="/admin/treatments">Back</Link>
                </div>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div v-if="Object.keys(form.errors).length" class="admin-error-summary">
                <b>Please fix the highlighted fields.</b>
                <span>{{ Object.keys(form.errors).length }} validation issue(s) found.</span>
            </div>

            <div class="admin-editor-tabs" role="tablist" aria-label="Treatment editor sections">
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

            <form class="admin-treatment-form" @submit.prevent="submit">
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
                            <p>This controls the public URL and search metadata for the treatment page.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Search basics</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>URL slug</span>
                                <input v-model="form.slug" type="text" @blur="form.slug = slugify(form.slug)">
                                <small v-if="errorFor('slug')">{{ errorFor('slug') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Canonical URL</span>
                                <input v-model="form.seo_canonical_url" type="url" placeholder="https://example.com/treatments/example">
                                <small v-if="errorFor('seo_canonical_url')">{{ errorFor('seo_canonical_url') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>SEO title</span>
                                <input v-model="form.seo_title" type="text" maxlength="180">
                                <small v-if="errorFor('seo_title')">{{ errorFor('seo_title') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Breadcrumb label</span>
                                <input v-model="form.seo_breadcrumb_label" type="text" maxlength="120">
                                <small v-if="errorFor('seo_breadcrumb_label')">{{ errorFor('seo_breadcrumb_label') }}</small>
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
                                    <img :src="openGraphPreviewImage" :alt="form.seo_og_image_alt || form.hero_image_alt || form.home_image_alt || ''">
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
                                <small>{{ form.seo_schema_type || 'MedicalProcedure' }} · FAQ schema uses the FAQ rows from Content</small>
                                <p>{{ schemaPreviewDescription }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'home'"
                    id="admin-panel-home"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-home"
                >
                    <div class="admin-panel-head">
                        <span>02</span>
                        <div>
                            <h3>Homepage treatment container</h3>
                            <p>This controls the full-width treatment band shown on the homepage.</p>
                        </div>
                    </div>

                    <div class="admin-form-grid">
                        <label class="admin-field small">
                            <span>Sort order</span>
                            <input v-model="form.sort_order" type="number" min="1">
                            <small v-if="errorFor('sort_order')">{{ errorFor('sort_order') }}</small>
                        </label>

                        <label class="admin-field small">
                            <span>Tone</span>
                            <select v-model="form.tone">
                                <option v-for="tone in tones" :key="tone.value" :value="tone.value">{{ tone.label }}</option>
                            </select>
                            <small v-if="errorFor('tone')">{{ errorFor('tone') }}</small>
                        </label>

                        <label class="admin-check admin-field-check">
                            <input v-model="form.is_active" type="checkbox">
                            <span>Visible on public website</span>
                        </label>

                        <label class="admin-field">
                            <span>Homepage title</span>
                            <input v-model="form.home_title" type="text">
                            <small v-if="errorFor('home_title')">{{ errorFor('home_title') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Homepage subtitle</span>
                            <input v-model="form.home_subtitle" type="text">
                            <small v-if="errorFor('home_subtitle')">{{ errorFor('home_subtitle') }}</small>
                        </label>

                        <label class="admin-field wide">
                            <span>Homepage description</span>
                            <textarea v-model="form.home_description" rows="3"></textarea>
                            <small v-if="errorFor('home_description')">{{ errorFor('home_description') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Homepage image path</span>
                            <input v-model="form.home_image" type="text" placeholder="/assets/example.jpg">
                            <small v-if="errorFor('home_image')">{{ errorFor('home_image') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Or upload homepage image</span>
                            <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('home_image_file', $event)">
                            <small v-if="errorFor('home_image_file')">{{ errorFor('home_image_file') }}</small>
                        </label>

                        <label class="admin-field wide">
                            <span>Homepage image alt text</span>
                            <input v-model="form.home_image_alt" type="text">
                            <small v-if="errorFor('home_image_alt')">{{ errorFor('home_image_alt') }}</small>
                        </label>

                        <label class="admin-field wide">
                            <span>Icon SVG inner markup</span>
                            <textarea v-model="form.home_icon_svg" rows="3"></textarea>
                            <small v-if="errorFor('home_icon_svg')">{{ errorFor('home_icon_svg') }}</small>
                        </label>
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
                        <span>03</span>
                        <div>
                            <h3>Treatment detail page</h3>
                            <p>This content renders inside the reusable treatment landing-page template.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Hero</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Category pill</span>
                                <input v-model="form.category" type="text">
                                <small v-if="errorFor('category')">{{ errorFor('category') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Detail title</span>
                                <input v-model="form.title" type="text">
                                <small v-if="errorFor('title')">{{ errorFor('title') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted title word</span>
                                <input v-model="form.title_accent" type="text">
                                <small v-if="errorFor('title_accent')">{{ errorFor('title_accent') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Tagline</span>
                                <input v-model="form.tagline" type="text">
                                <small v-if="errorFor('tagline')">{{ errorFor('tagline') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.summary"
                                class="admin-field wide"
                                label="Hero summary"
                                :error="errorFor('summary')"
                            />

                            <label class="admin-field">
                                <span>Hero image path</span>
                                <input v-model="form.hero_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('hero_image')">{{ errorFor('hero_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload hero image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('hero_image_file', $event)">
                                <small v-if="errorFor('hero_image_file')">{{ errorFor('hero_image_file') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Hero image alt text</span>
                                <input v-model="form.hero_image_alt" type="text">
                                <small v-if="errorFor('hero_image_alt')">{{ errorFor('hero_image_alt') }}</small>
                            </label>
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Hero facts</h5>
                                <button type="button" @click="addRow('facts', { label: '', value: '' })">Add fact</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(fact, index) in form.facts" :key="index" class="admin-repeat-row two">
                                    <label class="admin-field">
                                        <span>Label</span>
                                        <input v-model="fact.label" type="text">
                                        <small v-if="errorFor(`facts.${index}.label`)">{{ errorFor(`facts.${index}.label`) }}</small>
                                    </label>
                                    <label class="admin-field">
                                        <span>Value</span>
                                        <input v-model="fact.value" type="text">
                                        <small v-if="errorFor(`facts.${index}.value`)">{{ errorFor(`facts.${index}.value`) }}</small>
                                    </label>
                                    <button type="button" @click="removeRow('facts', index, { label: '', value: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Overview</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.overview_eyebrow" type="text">
                                <small v-if="errorFor('overview_eyebrow')">{{ errorFor('overview_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.overview_heading" type="text">
                                <small v-if="errorFor('overview_heading')">{{ errorFor('overview_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading word</span>
                                <input v-model="form.overview_heading_accent" type="text">
                                <small v-if="errorFor('overview_heading_accent')">{{ errorFor('overview_heading_accent') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.overview_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('overview_lede')"
                            />

                            <RichTextEditor
                                v-model="form.overview_body"
                                class="admin-field wide"
                                label="Body paragraphs"
                                min-height="260px"
                                :error="errorFor('overview_body')"
                            />

                            <label class="admin-field">
                                <span>Overview image path</span>
                                <input v-model="form.overview_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('overview_image')">{{ errorFor('overview_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload overview image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('overview_image_file', $event)">
                                <small v-if="errorFor('overview_image_file')">{{ errorFor('overview_image_file') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Overview image alt text</span>
                                <input v-model="form.overview_image_alt" type="text">
                                <small v-if="errorFor('overview_image_alt')">{{ errorFor('overview_image_alt') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Caption</span>
                                <textarea v-model="form.overview_caption" rows="3"></textarea>
                                <small v-if="errorFor('overview_caption')">{{ errorFor('overview_caption') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Suitability</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.suitability_eyebrow" type="text">
                                <small v-if="errorFor('suitability_eyebrow')">{{ errorFor('suitability_eyebrow') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.suitability_heading" type="text">
                                <small v-if="errorFor('suitability_heading')">{{ errorFor('suitability_heading') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Highlighted heading word</span>
                                <input v-model="form.suitability_heading_accent" type="text">
                                <small v-if="errorFor('suitability_heading_accent')">{{ errorFor('suitability_heading_accent') }}</small>
                            </label>
                            <RichTextEditor
                                v-model="form.suitability_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('suitability_lede')"
                            />
                        </div>

                        <div class="admin-repeat-split">
                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Usually a good fit</h5>
                                    <button type="button" @click="addRow('suitable_for', { text: '' })">Add item</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(item, index) in form.suitable_for" :key="index" class="admin-repeat-row">
                                        <label class="admin-field">
                                            <span>Text</span>
                                            <input v-model="item.text" type="text">
                                            <small v-if="errorFor(`suitable_for.${index}.text`)">{{ errorFor(`suitable_for.${index}.text`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('suitable_for', index, { text: '' })">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Treat something else first</h5>
                                    <button type="button" @click="addRow('not_suitable', { text: '' })">Add item</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(item, index) in form.not_suitable" :key="index" class="admin-repeat-row">
                                        <label class="admin-field">
                                            <span>Text</span>
                                            <input v-model="item.text" type="text">
                                            <small v-if="errorFor(`not_suitable.${index}.text`)">{{ errorFor(`not_suitable.${index}.text`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('not_suitable', index, { text: '' })">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Process</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.process_eyebrow" type="text">
                                <small v-if="errorFor('process_eyebrow')">{{ errorFor('process_eyebrow') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.process_heading" type="text">
                                <small v-if="errorFor('process_heading')">{{ errorFor('process_heading') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Highlighted heading word</span>
                                <input v-model="form.process_heading_accent" type="text">
                                <small v-if="errorFor('process_heading_accent')">{{ errorFor('process_heading_accent') }}</small>
                            </label>
                            <RichTextEditor
                                v-model="form.process_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('process_lede')"
                            />
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Steps</h5>
                                <button type="button" @click="addRow('steps', { title: '', duration: '', body: '' })">Add step</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(step, index) in form.steps" :key="index" class="admin-repeat-row step">
                                    <label class="admin-field">
                                        <span>Title</span>
                                        <input v-model="step.title" type="text">
                                        <small v-if="errorFor(`steps.${index}.title`)">{{ errorFor(`steps.${index}.title`) }}</small>
                                    </label>
                                    <label class="admin-field">
                                        <span>Duration</span>
                                        <input v-model="step.duration" type="text">
                                        <small v-if="errorFor(`steps.${index}.duration`)">{{ errorFor(`steps.${index}.duration`) }}</small>
                                    </label>
                                    <RichTextEditor
                                        v-model="step.body"
                                        class="admin-field wide"
                                        label="Body"
                                        :error="errorFor(`steps.${index}.body`)"
                                    />
                                    <button type="button" @click="removeRow('steps', index, { title: '', duration: '', body: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>FAQ</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.faq_eyebrow" type="text">
                                <small v-if="errorFor('faq_eyebrow')">{{ errorFor('faq_eyebrow') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Heading</span>
                                <input v-model="form.faq_heading" type="text">
                                <small v-if="errorFor('faq_heading')">{{ errorFor('faq_heading') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Highlighted heading word</span>
                                <input v-model="form.faq_heading_accent" type="text">
                                <small v-if="errorFor('faq_heading_accent')">{{ errorFor('faq_heading_accent') }}</small>
                            </label>
                            <RichTextEditor
                                v-model="form.faq_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('faq_lede')"
                            />
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Questions</h5>
                                <button type="button" @click="addRow('faqs', { question: '', answer: '' })">Add question</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(faq, index) in form.faqs" :key="index" class="admin-repeat-row faq">
                                    <label class="admin-field">
                                        <span>Question</span>
                                        <input v-model="faq.question" type="text">
                                        <small v-if="errorFor(`faqs.${index}.question`)">{{ errorFor(`faqs.${index}.question`) }}</small>
                                    </label>
                                    <RichTextEditor
                                        v-model="faq.answer"
                                        class="admin-field wide"
                                        label="Answer"
                                        min-height="180px"
                                        :error="errorFor(`faqs.${index}.answer`)"
                                    />
                                    <button type="button" @click="removeRow('faqs', index, { question: '', answer: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>CTA and contact</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>CTA heading</span>
                                <input v-model="form.cta_heading" type="text">
                                <small v-if="errorFor('cta_heading')">{{ errorFor('cta_heading') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Highlighted CTA word</span>
                                <input v-model="form.cta_heading_accent" type="text">
                                <small v-if="errorFor('cta_heading_accent')">{{ errorFor('cta_heading_accent') }}</small>
                            </label>
                            <RichTextEditor
                                v-model="form.cta_body"
                                class="admin-field wide"
                                label="CTA body"
                                :error="errorFor('cta_body')"
                            />
                            <label class="admin-field">
                                <span>WhatsApp number</span>
                                <input v-model="form.whatsapp_number" type="text">
                                <small v-if="errorFor('whatsapp_number')">{{ errorFor('whatsapp_number') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>WhatsApp message</span>
                                <input v-model="form.whatsapp_message" type="text">
                                <small v-if="errorFor('whatsapp_message')">{{ errorFor('whatsapp_message') }}</small>
                            </label>
                            <label class="admin-field">
                                <span>Phone</span>
                                <input v-model="form.phone" type="text">
                                <small v-if="errorFor('phone')">{{ errorFor('phone') }}</small>
                            </label>
                        </div>
                    </div>
                </section>

                <div class="admin-form-actions">
                    <Link class="admin-action-secondary" href="/admin/treatments">Cancel</Link>
                    <button class="admin-action-primary" type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save treatment' }}
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </form>
        </section>
    </AdminShell>
</template>
