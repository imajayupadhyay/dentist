<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';

const props = defineProps({
    siteHeader: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const activeTab = ref('identity');

const tabs = [
    { id: 'identity', label: 'Identity' },
    { id: 'navigation', label: 'Navigation' },
];

const tabFields = {
    identity: [
        'logo_path',
        'logo_file',
        'logo_alt',
        'logo_href',
        'brand_name',
        'brand_subtitle',
        'phone_label',
        'phone_href',
        'cta_label',
        'cta_href',
        'mobile_meta',
    ],
    navigation: [
        'nav_items',
    ],
};

const form = useForm({
    ...props.siteHeader,
    logo_file: null,
});

ensureNavItems();

watch(
    () => Object.keys(form.errors).join('|'),
    () => {
        const tab = tabs.find((item) => tabHasError(item.id));

        if (tab) {
            activeTab.value = tab.id;
        }
    },
);

const logoPreview = computed(() => form.logo_path || '/assets/logo.png');

function ensureNavItems() {
    if (! Array.isArray(form.nav_items) || form.nav_items.length === 0) {
        form.nav_items = [blankNavItem()];
    }

    form.nav_items.forEach((item) => {
        if (! Array.isArray(item.children)) {
            item.children = [];
        }
    });
}

function blankNavItem() {
    return {
        label: '',
        href: '',
        current_path: '',
        children: [],
    };
}

function blankChildItem() {
    return {
        label: '',
        href: '',
        current_path: '',
    };
}

function addNavItem() {
    form.nav_items.push(blankNavItem());
}

function removeNavItem(index) {
    form.nav_items.splice(index, 1);

    if (form.nav_items.length === 0) {
        form.nav_items.push(blankNavItem());
    }
}

function moveNavItem(index, direction) {
    const target = index + direction;

    if (target < 0 || target >= form.nav_items.length) {
        return;
    }

    const [item] = form.nav_items.splice(index, 1);
    form.nav_items.splice(target, 0, item);
}

function addChildItem(item) {
    if (! Array.isArray(item.children)) {
        item.children = [];
    }

    item.children.push(blankChildItem());
}

function removeChildItem(item, index) {
    item.children.splice(index, 1);
}

function moveChildItem(item, index, direction) {
    const target = index + direction;

    if (target < 0 || target >= item.children.length) {
        return;
    }

    const [child] = item.children.splice(index, 1);
    item.children.splice(target, 0, child);
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
        .post('/admin/header', {
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
    <Head title="Admin Header">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Header">
        <section class="admin-treatment-page admin-header-page">
            <div class="admin-treatment-toolbar admin-header-toolbar">
                <div>
                    <span class="admin-kicker">Global module</span>
                    <h2>Header</h2>
                    <p>Manage the public header logo, brand text, phone action, appointment CTA, and dropdown navigation tree.</p>
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

            <div class="admin-editor-tabs" role="tablist" aria-label="Header editor sections">
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

            <form class="admin-treatment-form admin-header-form" @submit.prevent="submit">
                <section
                    v-show="activeTab === 'identity'"
                    id="admin-panel-identity"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-identity"
                >
                    <div class="admin-panel-head">
                        <span>01</span>
                        <div>
                            <h3>Identity and actions</h3>
                            <p>These fields drive the header logo area, phone link, primary appointment button, and mobile drawer footer note.</p>
                        </div>
                    </div>

                    <div class="admin-header-logo-editor">
                        <div class="admin-header-logo-preview">
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
                                <span>Logo link</span>
                                <input v-model="form.logo_href" type="text" maxlength="255" placeholder="/">
                                <small v-if="errorFor('logo_href')">{{ errorFor('logo_href') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Brand text</h4>
                        <div class="admin-form-grid">
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
                        </div>
                    </div>

                    <div class="admin-subsection">
                        <h4>Header actions</h4>
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Phone label</span>
                                <input v-model="form.phone_label" type="text" maxlength="80" placeholder="+91 98200 00000">
                                <small v-if="errorFor('phone_label')">{{ errorFor('phone_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>Phone link</span>
                                <input v-model="form.phone_href" type="text" maxlength="255" placeholder="tel:+919820000000">
                                <small v-if="errorFor('phone_href')">{{ errorFor('phone_href') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>CTA label</span>
                                <input v-model="form.cta_label" type="text" maxlength="80">
                                <small v-if="errorFor('cta_label')">{{ errorFor('cta_label') }}</small>
                            </label>

                            <label class="admin-field">
                                <span>CTA link</span>
                                <input v-model="form.cta_href" type="text" maxlength="255" placeholder="#book">
                                <small v-if="errorFor('cta_href')">{{ errorFor('cta_href') }}</small>
                            </label>

                            <label class="admin-field wide">
                                <span>Mobile footer note</span>
                                <textarea v-model="form.mobile_meta" rows="3" maxlength="500"></textarea>
                                <small v-if="errorFor('mobile_meta')">{{ errorFor('mobile_meta') }}</small>
                            </label>
                        </div>
                    </div>
                </section>

                <section
                    v-show="activeTab === 'navigation'"
                    id="admin-panel-navigation"
                    class="admin-treatment-panel"
                    role="tabpanel"
                    aria-labelledby="admin-tab-navigation"
                >
                    <div class="admin-panel-head">
                        <span>02</span>
                        <div>
                            <h3>Navigation tree</h3>
                            <p>Top-level items render in the desktop pill and mobile drawer. Child rows render as dropdown links under their parent.</p>
                        </div>
                    </div>

                    <div class="admin-repeat-head">
                        <h4>Top-level items</h4>
                        <button type="button" @click="addNavItem">Add item</button>
                    </div>

                    <div class="admin-header-nav-list">
                        <article
                            v-for="(item, index) in form.nav_items"
                            :key="`nav-${index}`"
                            class="admin-header-nav-card"
                        >
                            <div class="admin-header-card-head">
                                <div>
                                    <span class="admin-status draft">Item {{ index + 1 }}</span>
                                    <h5>{{ item.label || 'New navigation item' }}</h5>
                                </div>

                                <div class="admin-header-row-actions">
                                    <button type="button" :disabled="index === 0" @click="moveNavItem(index, -1)">Up</button>
                                    <button type="button" :disabled="index === form.nav_items.length - 1" @click="moveNavItem(index, 1)">Down</button>
                                    <button type="button" class="danger" @click="removeNavItem(index)">Remove</button>
                                </div>
                            </div>

                            <div class="admin-form-grid">
                                <label class="admin-field">
                                    <span>Label</span>
                                    <input v-model="item.label" type="text" maxlength="80">
                                    <small v-if="errorFor(`nav_items.${index}.label`)">{{ errorFor(`nav_items.${index}.label`) }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Link</span>
                                    <input v-model="item.href" type="text" maxlength="255" placeholder="/about-us or #book">
                                    <small v-if="errorFor(`nav_items.${index}.href`)">{{ errorFor(`nav_items.${index}.href`) }}</small>
                                </label>

                                <label class="admin-field wide">
                                    <span>Active path override</span>
                                    <input v-model="item.current_path" type="text" maxlength="255" placeholder="/about-us">
                                    <small v-if="errorFor(`nav_items.${index}.current_path`)">{{ errorFor(`nav_items.${index}.current_path`) }}</small>
                                </label>
                            </div>

                            <div class="admin-header-children">
                                <div class="admin-repeat-head">
                                    <h5>Dropdown items</h5>
                                    <button type="button" @click="addChildItem(item)">Add dropdown item</button>
                                </div>

                                <div v-if="item.children.length" class="admin-repeat-list">
                                    <div
                                        v-for="(child, childIndex) in item.children"
                                        :key="`nav-${index}-child-${childIndex}`"
                                        class="admin-repeat-row admin-header-child-row"
                                    >
                                        <label class="admin-field">
                                            <span>Child label</span>
                                            <input v-model="child.label" type="text" maxlength="80">
                                            <small v-if="errorFor(`nav_items.${index}.children.${childIndex}.label`)">{{ errorFor(`nav_items.${index}.children.${childIndex}.label`) }}</small>
                                        </label>

                                        <label class="admin-field">
                                            <span>Child link</span>
                                            <input v-model="child.href" type="text" maxlength="255">
                                            <small v-if="errorFor(`nav_items.${index}.children.${childIndex}.href`)">{{ errorFor(`nav_items.${index}.children.${childIndex}.href`) }}</small>
                                        </label>

                                        <label class="admin-field">
                                            <span>Active path</span>
                                            <input v-model="child.current_path" type="text" maxlength="255" placeholder="/templates/example">
                                            <small v-if="errorFor(`nav_items.${index}.children.${childIndex}.current_path`)">{{ errorFor(`nav_items.${index}.children.${childIndex}.current_path`) }}</small>
                                        </label>

                                        <div class="admin-header-child-actions">
                                            <button type="button" :disabled="childIndex === 0" @click="moveChildItem(item, childIndex, -1)">Up</button>
                                            <button type="button" :disabled="childIndex === item.children.length - 1" @click="moveChildItem(item, childIndex, 1)">Down</button>
                                            <button type="button" class="danger" @click="removeChildItem(item, childIndex)">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <p v-else class="admin-header-empty-note">No dropdown items.</p>
                            </div>
                        </article>
                    </div>
                </section>

                <div class="admin-form-actions">
                    <button class="admin-action-primary" type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save header' }}
                    </button>
                </div>
            </form>
        </section>
    </AdminShell>
</template>
