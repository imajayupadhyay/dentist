<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';

const props = defineProps({
    siteFooter: {
        type: Object,
        required: true,
    },
    footerOptions: {
        type: Object,
        default: () => ({
            cta_icons: [],
            cta_action_variants: [],
            social_icons: [],
            contact_icons: [],
            link_group_sources: [],
        }),
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const activeTab = ref('cta');

const tabs = [
    { id: 'cta', label: 'CTA' },
    { id: 'brand', label: 'Brand' },
    { id: 'links', label: 'Links' },
    { id: 'contact', label: 'Contact' },
    { id: 'bottom', label: 'Bottom' },
];

const tabFields = {
    cta: ['cta_enabled', 'cta_icon', 'cta_title', 'cta_body', 'cta_actions'],
    brand: ['logo_path', 'logo_file', 'logo_alt', 'brand_name', 'brand_subtitle', 'brand_blurb', 'social_links'],
    links: ['link_groups'],
    contact: ['contact_title', 'contact_items'],
    bottom: ['bottom_copyright', 'bottom_location', 'back_to_top_label', 'back_to_top_href'],
};

const form = useForm({
    ...props.siteFooter,
    logo_file: null,
});

ensureRepeats();

const ctaIcons = computed(() => options('cta_icons', [
    { value: 'phone', label: 'Phone' },
    { value: 'whatsapp', label: 'WhatsApp' },
    { value: 'arrow', label: 'Arrow' },
    { value: 'link', label: 'Link' },
]));
const ctaActionVariants = computed(() => options('cta_action_variants', [
    { value: 'primary', label: 'White button' },
    { value: 'whatsapp', label: 'Translucent button' },
]));
const socialIcons = computed(() => options('social_icons', [
    { value: 'instagram', label: 'Instagram' },
    { value: 'facebook', label: 'Facebook' },
    { value: 'youtube', label: 'YouTube' },
    { value: 'map', label: 'Map' },
    { value: 'whatsapp', label: 'WhatsApp' },
    { value: 'link', label: 'Link' },
]));
const contactIcons = computed(() => options('contact_icons', [
    { value: 'location', label: 'Location' },
    { value: 'phone', label: 'Phone' },
    { value: 'email', label: 'Email' },
    { value: 'clock', label: 'Clock' },
    { value: 'link', label: 'Link' },
]));
const linkGroupSources = computed(() => options('link_group_sources', [
    { value: 'manual', label: 'Manual links' },
    { value: 'treatments', label: 'Active treatments' },
]));
const logoPreview = computed(() => form.logo_path || '/assets/logo.png');

watch(
    () => Object.keys(form.errors).join('|'),
    () => {
        const tab = tabs.find((item) => tabHasError(item.id));

        if (tab) {
            activeTab.value = tab.id;
        }
    },
);

function options(key, fallback) {
    return Array.isArray(props.footerOptions[key]) && props.footerOptions[key].length
        ? props.footerOptions[key]
        : fallback;
}

function ensureRepeats() {
    if (! Array.isArray(form.cta_actions) || form.cta_actions.length === 0) {
        form.cta_actions = [blankCtaAction()];
    }
    if (! Array.isArray(form.social_links)) {
        form.social_links = [];
    }
    if (! Array.isArray(form.link_groups) || form.link_groups.length === 0) {
        form.link_groups = [blankLinkGroup()];
    }
    if (! Array.isArray(form.contact_items)) {
        form.contact_items = [];
    }

    form.link_groups.forEach((group) => {
        if (! Array.isArray(group.links)) {
            group.links = [];
        }
        if (! group.source) {
            group.source = 'manual';
        }
    });
}

function blankCtaAction() {
    return {
        label: '',
        href: '',
        variant: 'primary',
        icon: 'arrow',
        aria_label: '',
    };
}

function blankSocialLink() {
    return {
        label: '',
        href: '',
        icon: 'link',
    };
}

function blankLinkGroup() {
    return {
        title: '',
        source: 'manual',
        links: [],
    };
}

function blankGroupLink() {
    return {
        label: '',
        href: '',
    };
}

function blankContactItem() {
    return {
        icon: 'link',
        label: '',
        href: '',
    };
}

function addRow(key, row) {
    form[key].push({ ...row });
}

function removeRow(key, index, fallback = null) {
    form[key].splice(index, 1);

    if (fallback && form[key].length === 0) {
        addRow(key, fallback);
    }
}

function moveRow(key, index, direction) {
    const target = index + direction;

    if (target < 0 || target >= form[key].length) {
        return;
    }

    const [item] = form[key].splice(index, 1);
    form[key].splice(target, 0, item);
}

function addGroupLink(group) {
    if (! Array.isArray(group.links)) {
        group.links = [];
    }

    group.links.push(blankGroupLink());
}

function removeGroupLink(group, index) {
    group.links.splice(index, 1);
}

function moveGroupLink(group, index, direction) {
    const target = index + direction;

    if (target < 0 || target >= group.links.length) {
        return;
    }

    const [item] = group.links.splice(index, 1);
    group.links.splice(target, 0, item);
}

function setFile(event) {
    form.logo_file = event.target.files?.[0] ?? null;
}

function submit() {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/admin/footer', {
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
    <Head title="Admin Footer">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Footer">
        <section class="admin-treatment-page admin-footer-page">
            <div class="admin-treatment-toolbar admin-footer-toolbar">
                <div>
                    <span class="admin-kicker">Global module</span>
                    <h2>Footer</h2>
                    <p>Manage the public footer CTA, brand block, social links, link groups, contact rows, and bottom bar content.</p>
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

            <div class="admin-editor-tabs" role="tablist" aria-label="Footer editor sections">
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

            <form class="admin-treatment-form admin-footer-form" @submit.prevent="submit">
                <section
                    v-show="activeTab === 'cta'"
                    id="admin-panel-cta"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-cta"
                >
                    <div class="admin-panel-head">
                        <span>01</span>
                        <div>
                            <h3>CTA section</h3>
                            <p>This controls the red emergency band above the footer columns.</p>
                        </div>
                    </div>

                    <div class="admin-form-grid">
                        <label class="admin-check admin-field-check">
                            <input v-model="form.cta_enabled" type="checkbox">
                            <span>Show footer CTA</span>
                        </label>

                        <label class="admin-field">
                            <span>CTA icon</span>
                            <select v-model="form.cta_icon">
                                <option v-for="icon in ctaIcons" :key="icon.value" :value="icon.value">{{ icon.label }}</option>
                            </select>
                            <small v-if="errorFor('cta_icon')">{{ errorFor('cta_icon') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>CTA title</span>
                            <input v-model="form.cta_title" type="text" maxlength="120">
                            <small v-if="errorFor('cta_title')">{{ errorFor('cta_title') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>CTA body</span>
                            <textarea v-model="form.cta_body" rows="3" maxlength="400"></textarea>
                            <small v-if="errorFor('cta_body')">{{ errorFor('cta_body') }}</small>
                        </label>
                    </div>

                    <div class="admin-repeat-block">
                        <div class="admin-repeat-head">
                            <h5>CTA actions</h5>
                            <button type="button" @click="addRow('cta_actions', blankCtaAction())">Add action</button>
                        </div>

                        <small v-if="errorFor('cta_actions')" class="admin-footer-error">{{ errorFor('cta_actions') }}</small>

                        <div class="admin-repeat-list">
                            <div
                                v-for="(action, index) in form.cta_actions"
                                :key="`cta-action-${index}`"
                                class="admin-repeat-row admin-footer-cta-action-row"
                            >
                                <label class="admin-field">
                                    <span>Label</span>
                                    <input v-model="action.label" type="text" maxlength="80">
                                    <small v-if="errorFor(`cta_actions.${index}.label`)">{{ errorFor(`cta_actions.${index}.label`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Link</span>
                                    <input v-model="action.href" type="text" maxlength="255">
                                    <small v-if="errorFor(`cta_actions.${index}.href`)">{{ errorFor(`cta_actions.${index}.href`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Style</span>
                                    <select v-model="action.variant">
                                        <option v-for="variant in ctaActionVariants" :key="variant.value" :value="variant.value">{{ variant.label }}</option>
                                    </select>
                                    <small v-if="errorFor(`cta_actions.${index}.variant`)">{{ errorFor(`cta_actions.${index}.variant`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Icon</span>
                                    <select v-model="action.icon">
                                        <option v-for="icon in ctaIcons" :key="icon.value" :value="icon.value">{{ icon.label }}</option>
                                    </select>
                                    <small v-if="errorFor(`cta_actions.${index}.icon`)">{{ errorFor(`cta_actions.${index}.icon`) }}</small>
                                </label>

                                <label class="admin-field wide">
                                    <span>ARIA label</span>
                                    <input v-model="action.aria_label" type="text" maxlength="160">
                                    <small v-if="errorFor(`cta_actions.${index}.aria_label`)">{{ errorFor(`cta_actions.${index}.aria_label`) }}</small>
                                </label>

                                <div class="admin-footer-row-actions">
                                    <button type="button" :disabled="index === 0" @click="moveRow('cta_actions', index, -1)">Up</button>
                                    <button type="button" :disabled="index === form.cta_actions.length - 1" @click="moveRow('cta_actions', index, 1)">Down</button>
                                    <button type="button" class="danger" @click="removeRow('cta_actions', index, blankCtaAction())">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'brand'"
                    id="admin-panel-brand"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-brand"
                >
                    <div class="admin-panel-head">
                        <span>02</span>
                        <div>
                            <h3>Brand and social links</h3>
                            <p>This controls the first footer column and the social icon row.</p>
                        </div>
                    </div>

                    <div class="admin-footer-logo-editor">
                        <div class="admin-footer-logo-preview">
                            <img :src="logoPreview" alt="">
                            <span>{{ form.logo_path || 'Using fallback logo' }}</span>
                        </div>

                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Logo path</span>
                                <input v-model="form.logo_path" type="text" maxlength="2048" placeholder="/assets/logo.png">
                                <small v-if="errorFor('logo_path')">{{ errorFor('logo_path') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Upload replacement logo</span>
                                <input type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml" @change="setFile">
                                <small v-if="errorFor('logo_file')">{{ errorFor('logo_file') }}</small>
                                <small v-else-if="form.logo_file">{{ form.logo_file.name }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Logo alt text</span>
                                <input v-model="form.logo_alt" type="text" maxlength="255">
                                <small v-if="errorFor('logo_alt')">{{ errorFor('logo_alt') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Brand name</span>
                                <input v-model="form.brand_name" type="text" maxlength="80">
                                <small v-if="errorFor('brand_name')">{{ errorFor('brand_name') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Brand subtitle</span>
                                <input v-model="form.brand_subtitle" type="text" maxlength="80">
                                <small v-if="errorFor('brand_subtitle')">{{ errorFor('brand_subtitle') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Brand blurb</span>
                                <textarea v-model="form.brand_blurb" rows="4" maxlength="500"></textarea>
                                <small v-if="errorFor('brand_blurb')">{{ errorFor('brand_blurb') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="admin-repeat-block">
                        <div class="admin-repeat-head">
                            <h5>Social links</h5>
                            <button type="button" @click="addRow('social_links', blankSocialLink())">Add social link</button>
                        </div>

                        <div v-if="form.social_links.length" class="admin-repeat-list">
                            <div
                                v-for="(social, index) in form.social_links"
                                :key="`social-${index}`"
                                class="admin-repeat-row admin-footer-social-row"
                            >
                                <label class="admin-field">
                                    <span>Label</span>
                                    <input v-model="social.label" type="text" maxlength="80">
                                    <small v-if="errorFor(`social_links.${index}.label`)">{{ errorFor(`social_links.${index}.label`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Link</span>
                                    <input v-model="social.href" type="text" maxlength="255">
                                    <small v-if="errorFor(`social_links.${index}.href`)">{{ errorFor(`social_links.${index}.href`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Icon</span>
                                    <select v-model="social.icon">
                                        <option v-for="icon in socialIcons" :key="icon.value" :value="icon.value">{{ icon.label }}</option>
                                    </select>
                                    <small v-if="errorFor(`social_links.${index}.icon`)">{{ errorFor(`social_links.${index}.icon`) }}</small>
                                </label>

                                <div class="admin-footer-row-actions">
                                    <button type="button" :disabled="index === 0" @click="moveRow('social_links', index, -1)">Up</button>
                                    <button type="button" :disabled="index === form.social_links.length - 1" @click="moveRow('social_links', index, 1)">Down</button>
                                    <button type="button" class="danger" @click="removeRow('social_links', index)">Remove</button>
                                </div>
                            </div>
                        </div>

                        <p v-else class="admin-footer-empty-note">No social links.</p>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'links'"
                    id="admin-panel-links"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-links"
                >
                    <div class="admin-panel-head">
                        <span>03</span>
                        <div>
                            <h3>Link groups</h3>
                            <p>Manage footer link columns. The Treatments group can stay connected to active treatment records.</p>
                        </div>
                    </div>

                    <div class="admin-repeat-head">
                        <h4>Footer columns</h4>
                        <button type="button" @click="addRow('link_groups', blankLinkGroup())">Add group</button>
                    </div>

                    <div class="admin-footer-group-list">
                        <article
                            v-for="(group, index) in form.link_groups"
                            :key="`group-${index}`"
                            class="admin-footer-group-card"
                        >
                            <div class="admin-footer-card-head">
                                <div>
                                    <span class="admin-status draft">Group {{ index + 1 }}</span>
                                    <h5>{{ group.title || 'New link group' }}</h5>
                                </div>

                                <div class="admin-footer-row-actions">
                                    <button type="button" :disabled="index === 0" @click="moveRow('link_groups', index, -1)">Up</button>
                                    <button type="button" :disabled="index === form.link_groups.length - 1" @click="moveRow('link_groups', index, 1)">Down</button>
                                    <button type="button" class="danger" @click="removeRow('link_groups', index, blankLinkGroup())">Remove</button>
                                </div>
                            </div>

                            <div class="admin-form-grid">
                                <label class="admin-field">
                                    <span>Group title</span>
                                    <input v-model="group.title" type="text" maxlength="80">
                                    <small v-if="errorFor(`link_groups.${index}.title`)">{{ errorFor(`link_groups.${index}.title`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Source</span>
                                    <select v-model="group.source">
                                        <option v-for="source in linkGroupSources" :key="source.value" :value="source.value">{{ source.label }}</option>
                                    </select>
                                    <small v-if="errorFor(`link_groups.${index}.source`)">{{ errorFor(`link_groups.${index}.source`) }}</small>
                                </label>
                            </div>

                            <div class="admin-footer-group-links">
                                <div class="admin-repeat-head">
                                    <h5>{{ group.source === 'treatments' ? 'Fallback links' : 'Links' }}</h5>
                                    <button type="button" @click="addGroupLink(group)">Add link</button>
                                </div>

                                <small v-if="errorFor(`link_groups.${index}.links`)" class="admin-footer-error">{{ errorFor(`link_groups.${index}.links`) }}</small>

                                <div v-if="group.links.length" class="admin-repeat-list">
                                    <div
                                        v-for="(link, linkIndex) in group.links"
                                        :key="`group-${index}-link-${linkIndex}`"
                                        class="admin-repeat-row admin-footer-group-link-row"
                                    >
                                        <label class="admin-field">
                                            <span>Label</span>
                                            <input v-model="link.label" type="text" maxlength="80">
                                            <small v-if="errorFor(`link_groups.${index}.links.${linkIndex}.label`)">{{ errorFor(`link_groups.${index}.links.${linkIndex}.label`) }}</small>
                                        </label>

                                        <label class="admin-field">
                                            <span>Link</span>
                                            <input v-model="link.href" type="text" maxlength="255">
                                            <small v-if="errorFor(`link_groups.${index}.links.${linkIndex}.href`)">{{ errorFor(`link_groups.${index}.links.${linkIndex}.href`) }}</small>
                                        </label>

                                        <div class="admin-footer-row-actions">
                                            <button type="button" :disabled="linkIndex === 0" @click="moveGroupLink(group, linkIndex, -1)">Up</button>
                                            <button type="button" :disabled="linkIndex === group.links.length - 1" @click="moveGroupLink(group, linkIndex, 1)">Down</button>
                                            <button type="button" class="danger" @click="removeGroupLink(group, linkIndex)">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <p v-else class="admin-footer-empty-note">No links.</p>
                            </div>
                        </article>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'contact'"
                    id="admin-panel-contact"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-contact"
                >
                    <div class="admin-panel-head">
                        <span>04</span>
                        <div>
                            <h3>Contact column</h3>
                            <p>This controls the footer contact section and each icon row inside it.</p>
                        </div>
                    </div>

                    <label class="admin-field">
                        <span>Contact title</span>
                        <input v-model="form.contact_title" type="text" maxlength="80">
                        <small v-if="errorFor('contact_title')">{{ errorFor('contact_title') }}</small>
                    </label>

                    <div class="admin-repeat-block">
                        <div class="admin-repeat-head">
                            <h5>Contact rows</h5>
                            <button type="button" @click="addRow('contact_items', blankContactItem())">Add contact row</button>
                        </div>

                        <div v-if="form.contact_items.length" class="admin-repeat-list">
                            <div
                                v-for="(item, index) in form.contact_items"
                                :key="`contact-${index}`"
                                class="admin-repeat-row admin-footer-contact-row"
                            >
                                <label class="admin-field">
                                    <span>Icon</span>
                                    <select v-model="item.icon">
                                        <option v-for="icon in contactIcons" :key="icon.value" :value="icon.value">{{ icon.label }}</option>
                                    </select>
                                    <small v-if="errorFor(`contact_items.${index}.icon`)">{{ errorFor(`contact_items.${index}.icon`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Text</span>
                                    <textarea v-model="item.label" rows="3" maxlength="300"></textarea>
                                    <small v-if="errorFor(`contact_items.${index}.label`)">{{ errorFor(`contact_items.${index}.label`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Link</span>
                                    <input v-model="item.href" type="text" maxlength="255">
                                    <small v-if="errorFor(`contact_items.${index}.href`)">{{ errorFor(`contact_items.${index}.href`) }}</small>
                                </label>

                                <div class="admin-footer-row-actions">
                                    <button type="button" :disabled="index === 0" @click="moveRow('contact_items', index, -1)">Up</button>
                                    <button type="button" :disabled="index === form.contact_items.length - 1" @click="moveRow('contact_items', index, 1)">Down</button>
                                    <button type="button" class="danger" @click="removeRow('contact_items', index)">Remove</button>
                                </div>
                            </div>
                        </div>

                        <p v-else class="admin-footer-empty-note">No contact rows.</p>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'bottom'"
                    id="admin-panel-bottom"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-bottom"
                >
                    <div class="admin-panel-head">
                        <span>05</span>
                        <div>
                            <h3>Bottom bar</h3>
                            <p>This controls the compact final footer row below the columns.</p>
                        </div>
                    </div>

                    <div class="admin-form-grid">
                        <label class="admin-field wide">
                            <span>Copyright text</span>
                            <input v-model="form.bottom_copyright" type="text" maxlength="180">
                            <small v-if="errorFor('bottom_copyright')">{{ errorFor('bottom_copyright') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Location pill</span>
                            <input v-model="form.bottom_location" type="text" maxlength="120">
                            <small v-if="errorFor('bottom_location')">{{ errorFor('bottom_location') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Back-to-top label</span>
                            <input v-model="form.back_to_top_label" type="text" maxlength="80">
                            <small v-if="errorFor('back_to_top_label')">{{ errorFor('back_to_top_label') }}</small>
                        </label>

                        <label class="admin-field">
                            <span>Back-to-top link</span>
                            <input v-model="form.back_to_top_href" type="text" maxlength="255">
                            <small v-if="errorFor('back_to_top_href')">{{ errorFor('back_to_top_href') }}</small>
                        </label>
                    </div>
                </section>

                <div class="admin-form-actions">
                    <button class="admin-action-primary" type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save footer' }}
                    </button>
                </div>
            </form>
        </section>
    </AdminShell>
</template>
