<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const year = computed(() => new Date().getFullYear());
const page = usePage();

const fallbackFooter = {
    cta_enabled: true,
    cta_icon: 'phone',
    cta_title: 'Dental emergency?',
    cta_body: 'A slot is held every weekday afternoon. Call before noon and you will be seen today.',
    cta_actions: [
        {
            label: 'Call +91 98200 00000',
            href: 'tel:+919820000000',
            variant: 'primary',
            icon: 'arrow',
            aria_label: '',
        },
        {
            label: 'WhatsApp',
            href: 'https://wa.me/919820000000?text=Hello%2C%20I%27d%20like%20to%20book%20a%20dental%20appointment.',
            variant: 'whatsapp',
            icon: 'whatsapp',
            aria_label: 'Message the clinic on WhatsApp',
        },
    ],
    logo_path: '/assets/logo.png',
    logo_alt: '',
    brand_name: 'Pushpa Patel',
    brand_subtitle: 'Dental Clinic',
    brand_blurb: 'Modern, unhurried dentistry in Bandra West since 2009. Implants, aligners, smile design, jaw joint care and full-mouth rehabilitation, all under one roof.',
    social_links: [
        { label: 'Instagram', href: '#', icon: 'instagram' },
        { label: 'Facebook', href: '#', icon: 'facebook' },
        { label: 'YouTube', href: '#', icon: 'youtube' },
        { label: 'Find us on Google Maps', href: '#', icon: 'map' },
    ],
    link_groups: [
        {
            title: 'Treatments',
            source: 'treatments',
            links: [
                { label: 'Painless dentistry', href: '/#treatments' },
                { label: 'Dental implants', href: '/#treatments' },
                { label: 'Invisible aligners', href: '/#treatments' },
                { label: 'Smile design', href: '/#treatments' },
                { label: 'Jaw joint & TMD', href: '/#treatments' },
                { label: "Kids' dentistry", href: '/#treatments' },
            ],
        },
        {
            title: 'Clinic',
            source: 'manual',
            links: [
                { label: 'About the clinic', href: '/about-us' },
                { label: 'Our dentists', href: '/about-us#team' },
                { label: 'Patient stories', href: '/#stories' },
                { label: 'Google reviews', href: '/#reviews' },
                { label: 'Find us', href: '/#contact' },
                { label: 'Book an appointment', href: '/#book' },
            ],
        },
    ],
    contact_title: 'Visit us',
    contact_items: [
        {
            icon: 'location',
            label: '2nd Floor, Turner House,\nLinking Road, Bandra West,\nMumbai 400050',
            href: '',
        },
        {
            icon: 'phone',
            label: '+91 22 2600 0000',
            href: 'tel:+912226000000',
        },
        {
            icon: 'email',
            label: 'care@pushpapateldental.in',
            href: 'mailto:care@pushpapateldental.in',
        },
        {
            icon: 'clock',
            label: 'Mon–Fri 9:30–19:30\nSat 9:30–15:00',
            href: '',
        },
    ],
    bottom_copyright: "© {year} Dr. Pushpa Patel's Dental Clinic. All rights reserved.",
    bottom_location: 'Bandra West, Mumbai',
    back_to_top_label: 'Back to top',
    back_to_top_href: '#top',
};

const iconPaths = {
    arrow: 'M5 12h14M13 6l6 6-6 6',
    phone: 'M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z',
    whatsapp: 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.019-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 21.785h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.002-5.45 4.437-9.884 9.888-9.884a9.82 9.82 0 016.988 2.896 9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.885-9.885 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.359.101 11.945c0 2.096.549 4.142 1.595 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.585 0 11.946-5.359 11.949-11.945a11.87 11.87 0 00-3.421-8.404',
    instagram: 'M12 2.2c3.2 0 3.6 0 4.8.07 1.2.05 1.8.25 2.2.42.6.22 1 .48 1.4.9.4.4.68.8.9 1.4.17.4.37 1 .42 2.2.06 1.2.07 1.6.07 4.8s0 3.6-.07 4.8c-.05 1.2-.25 1.8-.42 2.2-.22.6-.5 1-.9 1.4-.4.4-.8.68-1.4.9-.4.17-1 .37-2.2.42-1.2.06-1.6.07-4.8.07s-3.6 0-4.8-.07c-1.2-.05-1.8-.25-2.2-.42-.6-.22-1-.5-1.4-.9-.4-.4-.68-.8-.9-1.4-.17-.4-.37-1-.42-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.8c.05-1.2.25-1.8.42-2.2.22-.6.5-1 .9-1.4.4-.42.8-.68 1.4-.9.4-.17 1-.37 2.2-.42C8.4 2.2 8.8 2.2 12 2.2zm0 3.05A6.75 6.75 0 1018.75 12 6.75 6.75 0 0012 5.25zm0 11.13A4.38 4.38 0 1116.38 12 4.38 4.38 0 0112 16.38zm8.6-11.4a1.58 1.58 0 11-1.58-1.57 1.58 1.58 0 011.57 1.57z',
    facebook: 'M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5h1.65V4.6A22 22 0 0014.3 4.4c-2.4 0-4 1.45-4 4.15v2.35H7.6V14h2.7v8z',
    youtube: 'M22 12s0-3.2-.4-4.7a2.5 2.5 0 00-1.75-1.75C18.3 5.1 12 5.1 12 5.1s-6.3 0-7.85.45A2.5 2.5 0 002.4 7.3C2 8.8 2 12 2 12s0 3.2.4 4.7a2.5 2.5 0 001.75 1.75C5.7 18.9 12 18.9 12 18.9s6.3 0 7.85-.45A2.5 2.5 0 0021.6 16.7C22 15.2 22 12 22 12zM9.95 15.05v-6.1L15.2 12z',
    map: 'M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1114.5 9 2.5 2.5 0 0112 11.5z',
    location: 'M12 21s-7-5.8-7-11a7 7 0 1114 0c0 5.2-7 11-7 11zM12 12.4a2.4 2.4 0 100-4.8 2.4 2.4 0 000 4.8z',
    email: 'M5.4 5h13.2A2.4 2.4 0 0121 7.4v9.2a2.4 2.4 0 01-2.4 2.4H5.4A2.4 2.4 0 013 16.6V7.4A2.4 2.4 0 015.4 5zm-1 2.4l7.6 5.4 7.6-5.4',
    clock: 'M12 3a9 9 0 100 18 9 9 0 000-18zm0 4v5l3 2',
    link: 'M10.6 13.4a5 5 0 007.1 0l1.4-1.4a5 5 0 00-7.1-7.1l-.8.8m2.2 4.9a5 5 0 00-7.1 0L4.9 12a5 5 0 007.1 7.1l.8-.8',
};

const footer = computed(() => {
    const source = page.props.siteFooter;

    if (! source || typeof source !== 'object') {
        return fallbackFooter;
    }

    return {
        ...fallbackFooter,
        ...source,
        cta_actions: arrayOrFallback(source.cta_actions, fallbackFooter.cta_actions),
        social_links: Array.isArray(source.social_links) ? source.social_links : fallbackFooter.social_links,
        link_groups: arrayOrFallback(source.link_groups, fallbackFooter.link_groups),
        contact_items: Array.isArray(source.contact_items) ? source.contact_items : fallbackFooter.contact_items,
    };
});

const treatmentLinks = computed(() => {
    const links = page.props.treatmentLinks;

    return Array.isArray(links) && links.length ? links : [];
});

const linkGroups = computed(() => footer.value.link_groups
    .map((group) => {
        const fallbackLinks = Array.isArray(group.links) ? group.links : [];
        const links = group.source === 'treatments' && treatmentLinks.value.length
            ? treatmentLinks.value
            : fallbackLinks;

        return {
            ...group,
            links: links.filter((link) => link.label && link.href),
        };
    })
    .filter((group) => group.title && group.links.length));

const ctaActions = computed(() => footer.value.cta_actions.filter((action) => action.label && action.href));
const socialLinks = computed(() => footer.value.social_links.filter((link) => link.label && link.href));
const contactItems = computed(() => footer.value.contact_items.filter((item) => item.label));
const copyrightText = computed(() => String(footer.value.bottom_copyright || '').replaceAll('{year}', year.value));
const showCta = computed(() => Boolean(footer.value.cta_enabled && (footer.value.cta_title || footer.value.cta_body || ctaActions.value.length)));
const showBrand = computed(() => Boolean(footer.value.logo_path || footer.value.brand_name || footer.value.brand_blurb || socialLinks.value.length));
const showContact = computed(() => Boolean(footer.value.contact_title || contactItems.value.length));
const showBackToTop = computed(() => Boolean(footer.value.back_to_top_label && footer.value.back_to_top_href));
const showBottom = computed(() => Boolean(copyrightText.value || footer.value.bottom_location || showBackToTop.value));

function arrayOrFallback(value, fallback) {
    return Array.isArray(value) && value.length ? value : fallback;
}

function cleanHref(href) {
    return String(href || '').trim();
}

function normalizedHref(href) {
    const clean = cleanHref(href);
    const [withoutHash] = page.url.split('#');
    const currentPath = withoutHash.split('?')[0] || '/';

    if (clean === '') {
        return '#';
    }

    if (currentPath !== '/' && clean.startsWith('#')) {
        return `/${clean}`;
    }

    if (currentPath === '/' && clean.startsWith('/#')) {
        return clean.slice(1);
    }

    if (clean.startsWith(`${currentPath}#`)) {
        return clean.slice(currentPath.length);
    }

    return clean;
}

function linkComponent(href) {
    const clean = normalizedHref(href);

    if (clean.startsWith('/') && ! clean.startsWith('//')) {
        return Link;
    }

    return 'a';
}

function isExternal(href) {
    const clean = cleanHref(href).toLowerCase();

    return clean.startsWith('http://') || clean.startsWith('https://');
}

function targetFor(href) {
    return isExternal(href) ? '_blank' : null;
}

function relFor(href) {
    return isExternal(href) ? 'noopener' : null;
}

function iconPath(name) {
    return iconPaths[name] || iconPaths.link;
}

function isFillIcon(name) {
    return ['whatsapp', 'instagram', 'facebook', 'youtube', 'map'].includes(name);
}
</script>

<template>
    <footer class="ft">
        <div class="wrap">

            <div v-if="showCta" class="ft-cta">
                <span class="ic">
                    <svg
                        viewBox="0 0 24 24"
                        :class="isFillIcon(footer.cta_icon) ? 'fill-icon' : 'stroke-icon'"
                        :fill="isFillIcon(footer.cta_icon) ? 'currentColor' : 'none'"
                        :stroke="isFillIcon(footer.cta_icon) ? null : 'currentColor'"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ><path :d="iconPath(footer.cta_icon)"/></svg>
                </span>
                <span>
                    <b v-if="footer.cta_title">{{ footer.cta_title }}</b>
                    <span v-if="footer.cta_body">{{ footer.cta_body }}</span>
                </span>
                <div v-if="ctaActions.length" class="ft-cta-actions">
                    <component
                        :is="linkComponent(action.href)"
                        v-for="(action, index) in ctaActions"
                        :key="`${action.label}-${index}`"
                        class="btn"
                        :class="{ 'btn-wa': action.variant === 'whatsapp' }"
                        :href="normalizedHref(action.href)"
                        :target="targetFor(action.href)"
                        :rel="relFor(action.href)"
                        :aria-label="action.aria_label || null"
                    >
                        <svg
                            v-if="action.icon !== 'arrow'"
                            viewBox="0 0 24 24"
                            :class="isFillIcon(action.icon) ? 'fill-icon' : 'stroke-icon'"
                            :fill="isFillIcon(action.icon) ? 'currentColor' : 'none'"
                            :stroke="isFillIcon(action.icon) ? null : 'currentColor'"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        ><path :d="iconPath(action.icon)"/></svg>
                        {{ action.label }}
                        <svg
                            v-if="action.icon === 'arrow'"
                            class="stroke-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        ><path :d="iconPath(action.icon)"/></svg>
                    </component>
                </div>
            </div>

            <div class="ft-top">
                <div v-if="showBrand">
                    <div class="ft-brand">
                        <img v-if="footer.logo_path" :src="footer.logo_path" :alt="footer.logo_alt">
                        <span>
                            <b v-if="footer.brand_name">{{ footer.brand_name }}</b>
                            <span v-if="footer.brand_subtitle">{{ footer.brand_subtitle }}</span>
                        </span>
                    </div>
                    <p v-if="footer.brand_blurb" class="ft-blurb">{{ footer.brand_blurb }}</p>
                    <div v-if="socialLinks.length" class="socs">
                        <component
                            :is="linkComponent(social.href)"
                            v-for="(social, index) in socialLinks"
                            :key="`${social.label}-${index}`"
                            :href="normalizedHref(social.href)"
                            :target="targetFor(social.href)"
                            :rel="relFor(social.href)"
                            :aria-label="social.label"
                        >
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path :d="iconPath(social.icon)"/></svg>
                        </component>
                    </div>
                </div>

                <div v-for="(group, groupIndex) in linkGroups" :key="`${group.title}-${groupIndex}`">
                    <h4>{{ group.title }}</h4>
                    <ul>
                        <li v-for="(item, index) in group.links" :key="`${item.label}-${index}`">
                            <component
                                :is="linkComponent(item.href)"
                                :href="normalizedHref(item.href)"
                                :target="targetFor(item.href)"
                                :rel="relFor(item.href)"
                            >{{ item.label }}</component>
                        </li>
                    </ul>
                </div>

                <div v-if="showContact">
                    <h4 v-if="footer.contact_title">{{ footer.contact_title }}</h4>
                    <div v-if="contactItems.length" class="ft-contact">
                        <div v-for="(item, index) in contactItems" :key="`${item.label}-${index}`">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path :d="iconPath(item.icon)"/></svg>
                            <span>
                                <component
                                    :is="linkComponent(item.href)"
                                    v-if="item.href"
                                    :href="normalizedHref(item.href)"
                                    :target="targetFor(item.href)"
                                    :rel="relFor(item.href)"
                                >{{ item.label }}</component>
                                <template v-else>{{ item.label }}</template>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showBottom" class="ft-bot">
                <span v-if="copyrightText" class="ft-copy">{{ copyrightText }}</span>
                <span v-if="footer.bottom_location" class="ft-loc">{{ footer.bottom_location }}</span>
                <component
                    :is="linkComponent(footer.back_to_top_href)"
                    v-if="showBackToTop"
                    class="ft-up"
                    :href="normalizedHref(footer.back_to_top_href)"
                >
                    {{ footer.back_to_top_label }}
                    <svg viewBox="0 0 24 24"><path d="M12 19V5M6 11l6-6 6 6"/></svg>
                </component>
            </div>
        </div>
    </footer>
</template>
