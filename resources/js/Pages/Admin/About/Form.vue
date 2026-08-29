<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';
import RichTextEditor from '../Components/RichTextEditor.vue';

const props = defineProps({
    aboutPage: {
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
        'masthead_lead_image_file',
        'masthead_inset_image',
        'masthead_inset_image_file',
        'masthead_proof_stars',
        'masthead_proof_rating',
        'masthead_proof_text',
        'figures',
        'note_eyebrow',
        'note_image',
        'note_image_file',
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
        'team_image_file',
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
    ],
};

const form = useForm({
    ...props.aboutPage,
    masthead_lead_image_file: null,
    masthead_inset_image_file: null,
    note_image_file: null,
    team_image_file: null,
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

const seoPreviewTitle = computed(() => form.seo_title || 'About us');
const seoPreviewDescription = computed(() => form.seo_description || textOnly(form.masthead_lede) || 'The search description will appear here once added.');
const seoPreviewUrl = computed(() => form.seo_canonical_url || '/about-us');
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
const openGraphPreviewImage = computed(() => form.seo_og_image || form.masthead_lead_image || form.team_image || '');
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
    if (! Array.isArray(form.masthead_meta) || form.masthead_meta.length === 0) {
        form.masthead_meta = [{ text: '' }];
    }
    if (! Array.isArray(form.figures) || form.figures.length === 0) {
        form.figures = [blankFigure()];
    }
    if (! Array.isArray(form.values_items) || form.values_items.length === 0) {
        form.values_items = [blankValue()];
    }
    if (! Array.isArray(form.clinicians) || form.clinicians.length === 0) {
        form.clinicians = [{ name: '', role: '' }];
    }
    if (! Array.isArray(form.team_chips) || form.team_chips.length === 0) {
        form.team_chips = [{ text: '' }];
    }
}

function blankFigure() {
    return {
        count: '',
        decimals: '',
        suffix: '',
        prefix: '',
        value: '',
        label: '',
    };
}

function blankValue() {
    return {
        num: '',
        title: '',
        copy: '',
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

function submit() {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/admin/about', {
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
    <Head title="Admin About">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="About">
        <section class="admin-treatment-page admin-about-page">
            <div class="admin-treatment-toolbar admin-about-toolbar">
                <div>
                    <span class="admin-kicker">Page module</span>
                    <h2>About</h2>
                    <p>Manage the About page SEO, masthead, figures, founder note, values, team and final CTA.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <a class="admin-action-secondary" href="/about-us" target="_blank" rel="noopener">View live</a>
                    <Link class="admin-action-secondary" href="/admin/dashboard">Dashboard</Link>
                </div>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div v-if="Object.keys(form.errors).length" class="admin-error-summary">
                <b>Please fix the highlighted fields.</b>
                <span>{{ Object.keys(form.errors).length }} validation issue(s) found.</span>
            </div>

            <div class="admin-editor-tabs" role="tablist" aria-label="About editor sections">
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

            <form class="admin-treatment-form admin-about-form" @submit.prevent="submit">
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
                            <p>This controls the About page search, social sharing and structured metadata.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Search basics</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Canonical URL</span>
                                <input v-model="form.seo_canonical_url" type="url" placeholder="https://example.com/about-us">
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
                                    <img :src="openGraphPreviewImage" :alt="form.seo_og_image_alt || form.masthead_lead_image_alt || ''">
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
                                <small>{{ form.seo_schema_type || 'Dentist' }} · AboutPage breadcrumb included</small>
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
                            <p>This controls every visible section on the About page.</p>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Masthead</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.masthead_eyebrow" type="text">
                                <small v-if="errorFor('masthead_eyebrow')">{{ errorFor('masthead_eyebrow') }}</small>
                            </label>

                            <label class="admin-field small">
                                <span>Proof stars</span>
                                <input v-model="form.masthead_proof_stars" type="number" min="0" max="5">
                                <small v-if="errorFor('masthead_proof_stars')">{{ errorFor('masthead_proof_stars') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading before highlight</span>
                                <input v-model="form.masthead_heading" type="text">
                                <small v-if="errorFor('masthead_heading')">{{ errorFor('masthead_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.masthead_heading_accent" type="text">
                                <small v-if="errorFor('masthead_heading_accent')">{{ errorFor('masthead_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading after highlight</span>
                                <input v-model="form.masthead_heading_suffix" type="text">
                                <small v-if="errorFor('masthead_heading_suffix')">{{ errorFor('masthead_heading_suffix') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Proof rating</span>
                                <input v-model="form.masthead_proof_rating" type="text">
                                <small v-if="errorFor('masthead_proof_rating')">{{ errorFor('masthead_proof_rating') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Proof text</span>
                                <input v-model="form.masthead_proof_text" type="text">
                                <small v-if="errorFor('masthead_proof_text')">{{ errorFor('masthead_proof_text') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.masthead_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('masthead_lede')"
                            />

                            <label class="admin-field">
                                <span>Primary button label</span>
                                <input v-model="form.masthead_primary_label" type="text">
                                <small v-if="errorFor('masthead_primary_label')">{{ errorFor('masthead_primary_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Primary button href</span>
                                <input v-model="form.masthead_primary_href" type="text">
                                <small v-if="errorFor('masthead_primary_href')">{{ errorFor('masthead_primary_href') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Secondary button label</span>
                                <input v-model="form.masthead_secondary_label" type="text">
                                <small v-if="errorFor('masthead_secondary_label')">{{ errorFor('masthead_secondary_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Secondary button href</span>
                                <input v-model="form.masthead_secondary_href" type="text">
                                <small v-if="errorFor('masthead_secondary_href')">{{ errorFor('masthead_secondary_href') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Lead image path</span>
                                <input v-model="form.masthead_lead_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('masthead_lead_image')">{{ errorFor('masthead_lead_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload lead image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('masthead_lead_image_file', $event)">
                                <small v-if="errorFor('masthead_lead_image_file')">{{ errorFor('masthead_lead_image_file') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Lead image alt text</span>
                                <input v-model="form.masthead_lead_image_alt" type="text">
                                <small v-if="errorFor('masthead_lead_image_alt')">{{ errorFor('masthead_lead_image_alt') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Inset image path</span>
                                <input v-model="form.masthead_inset_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('masthead_inset_image')">{{ errorFor('masthead_inset_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload inset image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('masthead_inset_image_file', $event)">
                                <small v-if="errorFor('masthead_inset_image_file')">{{ errorFor('masthead_inset_image_file') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Inset image alt text</span>
                                <input v-model="form.masthead_inset_image_alt" type="text">
                                <small v-if="errorFor('masthead_inset_image_alt')">{{ errorFor('masthead_inset_image_alt') }}</small>
                            </label>
                        </div>

                        <div class="admin-repeat-block">
                            <div class="admin-repeat-head">
                                <h5>Masthead meta items</h5>
                                <button type="button" @click="addRow('masthead_meta', { text: '' })">Add item</button>
                            </div>
                            <div class="admin-repeat-list">
                                <div v-for="(item, index) in form.masthead_meta" :key="index" class="admin-repeat-row">
                                    <label class="admin-field">
                                        <span>Text</span>
                                        <input v-model="item.text" type="text">
                                        <small v-if="errorFor(`masthead_meta.${index}.text`)">{{ errorFor(`masthead_meta.${index}.text`) }}</small>
                                    </label>
                                    <button type="button" @click="removeRow('masthead_meta', index, { text: '' })">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <div class="admin-repeat-head">
                            <h4>Figures</h4>
                            <button type="button" @click="addRow('figures', blankFigure())">Add figure</button>
                        </div>

                        <div class="admin-repeat-list">
                            <div v-for="(figure, index) in form.figures" :key="index" class="admin-repeat-row about-figure">
                                <label class="admin-field">
                                    <span>Animated count</span>
                                    <input v-model="figure.count" type="number" step="0.1">
                                    <small v-if="errorFor(`figures.${index}.count`)">{{ errorFor(`figures.${index}.count`) }}</small>
                                </label>
                                <label class="admin-field">
                                    <span>Final value</span>
                                    <input v-model="figure.value" type="text">
                                    <small v-if="errorFor(`figures.${index}.value`)">{{ errorFor(`figures.${index}.value`) }}</small>
                                </label>
                                <label class="admin-field">
                                    <span>Label</span>
                                    <input v-model="figure.label" type="text">
                                    <small v-if="errorFor(`figures.${index}.label`)">{{ errorFor(`figures.${index}.label`) }}</small>
                                </label>
                                <label class="admin-field">
                                    <span>Prefix</span>
                                    <input v-model="figure.prefix" type="text">
                                    <small v-if="errorFor(`figures.${index}.prefix`)">{{ errorFor(`figures.${index}.prefix`) }}</small>
                                </label>
                                <label class="admin-field">
                                    <span>Suffix</span>
                                    <input v-model="figure.suffix" type="text">
                                    <small v-if="errorFor(`figures.${index}.suffix`)">{{ errorFor(`figures.${index}.suffix`) }}</small>
                                </label>
                                <label class="admin-field">
                                    <span>Decimals</span>
                                    <input v-model="figure.decimals" type="number" min="0" max="3">
                                    <small v-if="errorFor(`figures.${index}.decimals`)">{{ errorFor(`figures.${index}.decimals`) }}</small>
                                </label>
                                <button type="button" @click="removeRow('figures', index, blankFigure())">Remove</button>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Founder's note</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.note_eyebrow" type="text">
                                <small v-if="errorFor('note_eyebrow')">{{ errorFor('note_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Signature mark</span>
                                <input v-model="form.note_signature" type="text">
                                <small v-if="errorFor('note_signature')">{{ errorFor('note_signature') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Quote</span>
                                <textarea v-model="form.note_quote" rows="3"></textarea>
                                <small v-if="errorFor('note_quote')">{{ errorFor('note_quote') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.note_body"
                                class="admin-field wide"
                                label="Body"
                                min-height="180px"
                                :error="errorFor('note_body')"
                            />

                            <label class="admin-field">
                                <span>Founder name</span>
                                <input v-model="form.note_name" type="text">
                                <small v-if="errorFor('note_name')">{{ errorFor('note_name') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Founder role</span>
                                <input v-model="form.note_role" type="text">
                                <small v-if="errorFor('note_role')">{{ errorFor('note_role') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Portrait image path</span>
                                <input v-model="form.note_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('note_image')">{{ errorFor('note_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload portrait</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('note_image_file', $event)">
                                <small v-if="errorFor('note_image_file')">{{ errorFor('note_image_file') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Portrait alt text</span>
                                <input v-model="form.note_image_alt" type="text">
                                <small v-if="errorFor('note_image_alt')">{{ errorFor('note_image_alt') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <div class="admin-repeat-head">
                            <h4>Values</h4>
                            <button type="button" @click="addRow('values_items', blankValue())">Add value</button>
                        </div>

                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.values_eyebrow" type="text">
                                <small v-if="errorFor('values_eyebrow')">{{ errorFor('values_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading before highlight</span>
                                <input v-model="form.values_heading" type="text">
                                <small v-if="errorFor('values_heading')">{{ errorFor('values_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.values_heading_accent" type="text">
                                <small v-if="errorFor('values_heading_accent')">{{ errorFor('values_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading after highlight</span>
                                <input v-model="form.values_heading_suffix" type="text">
                                <small v-if="errorFor('values_heading_suffix')">{{ errorFor('values_heading_suffix') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.values_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('values_lede')"
                            />
                        </div>

                        <div class="admin-home-card-list">
                            <article v-for="(value, index) in form.values_items" :key="index" class="admin-home-card">
                                <div class="admin-repeat-head">
                                    <h5>Value {{ index + 1 }}</h5>
                                    <button type="button" @click="removeRow('values_items', index, blankValue())">Remove</button>
                                </div>

                                <div class="admin-form-grid">
                                    <label class="admin-field small">
                                        <span>Number</span>
                                        <input v-model="value.num" type="text">
                                        <small v-if="errorFor(`values_items.${index}.num`)">{{ errorFor(`values_items.${index}.num`) }}</small>
                                    </label>

                                    <label class="admin-field">
                                        <span>Title</span>
                                        <input v-model="value.title" type="text">
                                        <small v-if="errorFor(`values_items.${index}.title`)">{{ errorFor(`values_items.${index}.title`) }}</small>
                                    </label>

                                    <RichTextEditor
                                        v-model="value.copy"
                                        class="admin-field wide"
                                        label="Copy"
                                        :error="errorFor(`values_items.${index}.copy`)"
                                    />
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Team</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Eyebrow</span>
                                <input v-model="form.team_eyebrow" type="text">
                                <small v-if="errorFor('team_eyebrow')">{{ errorFor('team_eyebrow') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading before highlight</span>
                                <input v-model="form.team_heading" type="text">
                                <small v-if="errorFor('team_heading')">{{ errorFor('team_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.team_heading_accent" type="text">
                                <small v-if="errorFor('team_heading_accent')">{{ errorFor('team_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading after highlight</span>
                                <input v-model="form.team_heading_suffix" type="text">
                                <small v-if="errorFor('team_heading_suffix')">{{ errorFor('team_heading_suffix') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.team_lede"
                                class="admin-field wide"
                                label="Lede"
                                :error="errorFor('team_lede')"
                            />

                            <label class="admin-field">
                                <span>Team image path</span>
                                <input v-model="form.team_image" type="text" placeholder="/assets/example.jpg">
                                <small v-if="errorFor('team_image')">{{ errorFor('team_image') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Or upload team image</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp" @change="setFile('team_image_file', $event)">
                                <small v-if="errorFor('team_image_file')">{{ errorFor('team_image_file') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Team image alt text</span>
                                <input v-model="form.team_image_alt" type="text">
                                <small v-if="errorFor('team_image_alt')">{{ errorFor('team_image_alt') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Image caption</span>
                                <textarea v-model="form.team_caption" rows="3"></textarea>
                                <small v-if="errorFor('team_caption')">{{ errorFor('team_caption') }}</small>
                            </label>
                        </div>

                        <div class="admin-repeat-split">
                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Clinicians</h5>
                                    <button type="button" @click="addRow('clinicians', { name: '', role: '' })">Add clinician</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(clinician, index) in form.clinicians" :key="index" class="admin-repeat-row two">
                                        <label class="admin-field">
                                            <span>Name</span>
                                            <input v-model="clinician.name" type="text">
                                            <small v-if="errorFor(`clinicians.${index}.name`)">{{ errorFor(`clinicians.${index}.name`) }}</small>
                                        </label>
                                        <label class="admin-field">
                                            <span>Role</span>
                                            <input v-model="clinician.role" type="text">
                                            <small v-if="errorFor(`clinicians.${index}.role`)">{{ errorFor(`clinicians.${index}.role`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('clinicians', index, { name: '', role: '' })">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-repeat-block">
                                <div class="admin-repeat-head">
                                    <h5>Certification chips</h5>
                                    <button type="button" @click="addRow('team_chips', { text: '' })">Add chip</button>
                                </div>
                                <div class="admin-repeat-list">
                                    <div v-for="(chip, index) in form.team_chips" :key="index" class="admin-repeat-row">
                                        <label class="admin-field">
                                            <span>Text</span>
                                            <input v-model="chip.text" type="text">
                                            <small v-if="errorFor(`team_chips.${index}.text`)">{{ errorFor(`team_chips.${index}.text`) }}</small>
                                        </label>
                                        <button type="button" @click="removeRow('team_chips', index, { text: '' })">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>CTA</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Heading before highlight</span>
                                <input v-model="form.cta_heading" type="text">
                                <small v-if="errorFor('cta_heading')">{{ errorFor('cta_heading') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Highlighted heading text</span>
                                <input v-model="form.cta_heading_accent" type="text">
                                <small v-if="errorFor('cta_heading_accent')">{{ errorFor('cta_heading_accent') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Heading after highlight</span>
                                <input v-model="form.cta_heading_suffix" type="text">
                                <small v-if="errorFor('cta_heading_suffix')">{{ errorFor('cta_heading_suffix') }}</small>
                            </label>

                            <RichTextEditor
                                v-model="form.cta_body"
                                class="admin-field wide"
                                label="Body"
                                :error="errorFor('cta_body')"
                            />

                            <label class="admin-field">
                                <span>Primary button label</span>
                                <input v-model="form.cta_primary_label" type="text">
                                <small v-if="errorFor('cta_primary_label')">{{ errorFor('cta_primary_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Primary button href</span>
                                <input v-model="form.cta_primary_href" type="text">
                                <small v-if="errorFor('cta_primary_href')">{{ errorFor('cta_primary_href') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Secondary button label</span>
                                <input v-model="form.cta_secondary_label" type="text">
                                <small v-if="errorFor('cta_secondary_label')">{{ errorFor('cta_secondary_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Secondary button href</span>
                                <input v-model="form.cta_secondary_href" type="text">
                                <small v-if="errorFor('cta_secondary_href')">{{ errorFor('cta_secondary_href') }}</small>
                            </label>
                        </div>
                    </div>
                </section>

                <div class="admin-form-actions">
                    <Link class="admin-action-secondary" href="/admin/dashboard">Cancel</Link>
                    <button class="admin-action-primary" type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save about page' }}
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </form>
        </section>
    </AdminShell>
</template>
