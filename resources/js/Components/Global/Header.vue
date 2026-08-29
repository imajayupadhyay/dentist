<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const slim = ref(false);
const open = ref(false);
const mobileOpenGroups = ref(new Set());
const page = usePage();

const fallbackHeader = {
    logo_path: '/assets/logo.png',
    logo_alt: '',
    logo_href: '/',
    brand_name: 'Pushpa Patel',
    brand_subtitle: 'Dental Clinic',
    phone_label: '+91 98200 00000',
    phone_href: 'tel:+919820000000',
    cta_label: 'Book appointment',
    cta_href: '#book',
    mobile_meta: '2nd Floor, Turner House, Linking Road, Bandra West, Mumbai 400050\nMon-Fri 9:30-19:30 | Sat 9:30-15:00',
    nav_items: [
        { label: 'About Us', href: '/about-us', current_path: '/about-us', children: [] },
        { label: 'Treatments', href: '/#treatments', current_path: '', children: [] },
        { label: 'Doctors', href: '/about-us#team', current_path: '', children: [] },
        { label: 'Reviews', href: '/#reviews', current_path: '', children: [] },
        { label: 'Contact', href: '/#contact', current_path: '', children: [] },
    ],
};

const header = computed(() => {
    const source = page.props.siteHeader;

    if (! source || typeof source !== 'object') {
        return fallbackHeader;
    }

    const navItems = Array.isArray(source.nav_items) && source.nav_items.length
        ? source.nav_items
        : fallbackHeader.nav_items;

    return {
        ...fallbackHeader,
        ...source,
        nav_items: navItems,
    };
});

const links = computed(() => header.value.nav_items);
const currentPath = computed(() => {
    const [withoutHash] = page.url.split('#');

    return withoutHash.split('?')[0] || '/';
});

const brandHref = computed(() => {
    const href = cleanHref(header.value.logo_href || '/');

    if (href === '/' && currentPath.value === '/') {
        return '#top';
    }

    return normalizedHref(href);
});
const brandComponent = computed(() => linkComponent(brandHref.value));
const ctaHref = computed(() => normalizedHref(header.value.cta_href));
const ctaComponent = computed(() => linkComponent(ctaHref.value));
const showPhone = computed(() => Boolean(cleanHref(header.value.phone_label) && cleanHref(header.value.phone_href)));
const showCta = computed(() => Boolean(cleanHref(header.value.cta_label) && cleanHref(header.value.cta_href)));
const brandLabel = computed(() => [header.value.brand_name, header.value.brand_subtitle].filter(Boolean).join(' '));

function cleanHref(href) {
    return String(href || '').trim();
}

function normalizedHref(href) {
    const clean = cleanHref(href);

    if (clean === '') {
        return '#';
    }

    if (currentPath.value !== '/' && clean.startsWith('#')) {
        return `/${clean}`;
    }

    if (currentPath.value === '/' && clean.startsWith('/#')) {
        return clean.slice(1);
    }

    if (clean.startsWith(`${currentPath.value}#`)) {
        return clean.slice(currentPath.value.length);
    }

    return clean;
}

function linkComponent(href) {
    const clean = cleanHref(href);

    if (clean.startsWith('/') && ! clean.startsWith('//')) {
        return Link;
    }

    return 'a';
}

function hasChildren(item) {
    return Array.isArray(item.children) && item.children.length > 0;
}

function navigationKey(item, index) {
    return `${index}-${item.label || 'item'}-${item.href || 'dropdown'}`;
}

function targetPath(item) {
    const explicit = cleanHref(item.current_path);

    if (explicit !== '') {
        return explicit.split('#')[0].split('?')[0] || '/';
    }

    const href = cleanHref(item.href);

    if (! href.startsWith('/') || href.startsWith('/#') || href.includes('#')) {
        return '';
    }

    return href.split('?')[0] || '/';
}

function isCurrent(item) {
    const target = targetPath(item);

    if (target && target === currentPath.value) {
        return true;
    }

    return hasChildren(item) && item.children.some((child) => isCurrent(child));
}

function mobileGroupOpen(index) {
    return mobileOpenGroups.value.has(index);
}

function toggleMobileGroup(index) {
    const next = new Set(mobileOpenGroups.value);

    if (next.has(index)) {
        next.delete(index);
    } else {
        next.add(index);
    }

    mobileOpenGroups.value = next;
}

function closeMenu() {
    open.value = false;
    mobileOpenGroups.value = new Set();
}

function onScroll() {
    slim.value = window.scrollY > 20;
}

function onKeydown(event) {
    if (event.key === 'Escape' && open.value) {
        closeMenu();
    }
}

/** The drawer is mobile-only — never leave it open behind a desktop layout. */
function onResize() {
    if (window.innerWidth > 860) {
        closeMenu();
    }
}

// Lock the page behind the drawer while it is open.
watch(open, (isOpen) => {
    document.body.classList.toggle('mnav-lock', isOpen);

    if (! isOpen) {
        mobileOpenGroups.value = new Set();
    }
});

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);
    window.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('resize', onResize);
    window.removeEventListener('keydown', onKeydown);
    document.body.classList.remove('mnav-lock');
});
</script>

<template>
    <header class="nav" :class="{ slim, open }">
        <div class="nav-in">
            <component :is="brandComponent" class="brand" :href="brandHref" :aria-label="`${brandLabel} home`">
                <img :src="header.logo_path" :alt="header.logo_alt">
                <span>
                    <b>{{ header.brand_name }}</b>
                    <span v-if="header.brand_subtitle">{{ header.brand_subtitle }}</span>
                </span>
            </component>

            <nav class="nav-links" aria-label="Primary">
                <div
                    v-for="(link, index) in links"
                    :key="navigationKey(link, index)"
                    class="nav-item"
                    :class="{ 'has-menu': hasChildren(link), current: isCurrent(link) }"
                >
                    <component
                        :is="linkComponent(normalizedHref(link.href))"
                        v-if="cleanHref(link.href)"
                        class="nav-trigger"
                        :href="normalizedHref(link.href)"
                        :aria-current="isCurrent(link) ? 'page' : null"
                        :aria-haspopup="hasChildren(link) ? 'true' : null"
                    >
                        {{ link.label }}
                        <svg v-if="hasChildren(link)" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </component>

                    <button
                        v-else
                        class="nav-trigger"
                        type="button"
                        :aria-current="isCurrent(link) ? 'page' : null"
                        :aria-haspopup="hasChildren(link) ? 'true' : null"
                    >
                        {{ link.label }}
                        <svg v-if="hasChildren(link)" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </button>

                    <div v-if="hasChildren(link)" class="nav-dropdown" :aria-label="`${link.label} submenu`">
                        <component
                            :is="linkComponent(normalizedHref(child.href))"
                            v-for="(child, childIndex) in link.children"
                            :key="navigationKey(child, childIndex)"
                            :href="normalizedHref(child.href)"
                            :aria-current="isCurrent(child) ? 'page' : null"
                        >{{ child.label }}</component>
                    </div>
                </div>
            </nav>

            <a v-if="showPhone" class="nav-call" :href="header.phone_href">
                <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                {{ header.phone_label }}
            </a>

            <component v-if="showCta" :is="ctaComponent" class="btn btn-brand" :href="ctaHref">{{ header.cta_label }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </component>

            <button
                class="burger"
                :aria-label="open ? 'Close menu' : 'Menu'"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mnav"
                @click="open = !open"
            ><i></i></button>
        </div>
    </header>

    <!-- Full-screen drawer. Sibling of .nav on purpose — see mobile-nav.css. -->
    <div
        class="mnav"
        id="mnav"
        :class="{ open }"
        role="dialog"
        aria-modal="true"
        aria-label="Menu"
        :aria-hidden="open ? 'false' : 'true'"
    >
        <nav class="mnav-links" aria-label="Mobile">
            <template
                v-for="(link, index) in links"
                :key="navigationKey(link, index)"
            >
                <component
                    :is="linkComponent(normalizedHref(link.href))"
                    v-if="! hasChildren(link)"
                    class="mnav-item-link"
                    :href="normalizedHref(link.href)"
                    :aria-current="isCurrent(link) ? 'page' : null"
                    :style="{ '--i': index }"
                    :tabindex="open ? null : -1"
                    @click="closeMenu"
                >
                    {{ link.label }}
                    <svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
                </component>

                <div
                    v-else
                    class="mnav-group"
                    :class="{ open: mobileGroupOpen(index) }"
                    :style="{ '--i': index }"
                >
                    <div class="mnav-parent">
                        <component
                            :is="linkComponent(normalizedHref(link.href))"
                            v-if="cleanHref(link.href)"
                            class="mnav-parent-main"
                            :href="normalizedHref(link.href)"
                            :aria-current="isCurrent(link) ? 'page' : null"
                            :tabindex="open ? null : -1"
                            @click="closeMenu"
                        >{{ link.label }}</component>

                        <button
                            v-else
                            class="mnav-parent-main"
                            type="button"
                            :tabindex="open ? null : -1"
                            @click="toggleMobileGroup(index)"
                        >{{ link.label }}</button>

                        <button
                            class="mnav-toggle"
                            type="button"
                            :aria-expanded="mobileGroupOpen(index) ? 'true' : 'false'"
                            :aria-controls="`mnav-sub-${index}`"
                            :aria-label="`Toggle ${link.label} submenu`"
                            :tabindex="open ? null : -1"
                            @click="toggleMobileGroup(index)"
                        >
                            <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                    </div>

                    <div
                        :id="`mnav-sub-${index}`"
                        class="mnav-sub"
                    >
                        <component
                            :is="linkComponent(normalizedHref(child.href))"
                            v-for="(child, childIndex) in link.children"
                            :key="navigationKey(child, childIndex)"
                            :href="normalizedHref(child.href)"
                            :aria-current="isCurrent(child) ? 'page' : null"
                            :tabindex="open && mobileGroupOpen(index) ? null : -1"
                            @click="closeMenu"
                        >{{ child.label }}</component>
                    </div>
                </div>
            </template>
        </nav>

        <div class="mnav-foot">
            <a v-if="showPhone" class="mnav-call" :href="header.phone_href" :tabindex="open ? null : -1">
                <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                {{ header.phone_label }}
            </a>

            <component v-if="showCta" :is="ctaComponent" class="btn btn-brand" :href="ctaHref" :tabindex="open ? null : -1" @click="closeMenu">
                {{ header.cta_label }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </component>

            <p v-if="header.mobile_meta" class="mnav-meta">{{ header.mobile_meta }}</p>
        </div>
    </div>
</template>
