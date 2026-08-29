<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Masthead from './Components/Masthead.vue';
import Figures from './Components/Figures.vue';
import FoundersNote from './Components/FoundersNote.vue';
import Values from './Components/Values.vue';
import Team from './Components/Team.vue';
import Cta from './Components/Cta.vue';

const props = defineProps({
    aboutPage: {
        type: Object,
        default: () => ({}),
    },
});

const clinicName = "Dr. Pushpa Patel's Dental Clinic";
const seoMeta = computed(() => props.aboutPage.seo_meta || {});
const pageTitle = computed(() => seoMeta.value.title || 'About us');
const pageDescription = computed(() => seoMeta.value.description || "About Dr. Pushpa Patel's Dental Clinic - sixteen years of unhurried, plan-first dentistry on Linking Road, Bandra West, Mumbai.");
const canonicalUrl = computed(() => seoMeta.value.canonical || absoluteUrl('/about-us'));
const robotsContent = computed(() => seoMeta.value.robots || 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1');
const keywordContent = computed(() => seoMeta.value.keywords || '');
const openGraphTitle = computed(() => seoMeta.value.og?.title || pageTitle.value);
const openGraphDescription = computed(() => seoMeta.value.og?.description || pageDescription.value);
const openGraphImage = computed(() => seoMeta.value.og?.image || '');
const openGraphImageAlt = computed(() => seoMeta.value.og?.image_alt || openGraphTitle.value);
const twitterTitle = computed(() => seoMeta.value.twitter?.title || openGraphTitle.value);
const twitterDescription = computed(() => seoMeta.value.twitter?.description || openGraphDescription.value);
const twitterImage = computed(() => seoMeta.value.twitter?.image || openGraphImage.value);
const twitterCard = computed(() => seoMeta.value.twitter?.card || 'summary_large_image');
const structuredDataJson = computed(() => seoMeta.value.json_ld || '');

function absoluteUrl(value) {
    const clean = String(value || '').trim();

    if (! clean) {
        return '';
    }

    if (/^https?:\/\//i.test(clean)) {
        return clean;
    }

    const base = typeof window === 'undefined' ? '' : window.location.origin;

    if (! base) {
        return clean;
    }

    return new URL(clean.startsWith('/') ? clean : `/${clean}`, base).toString();
}
</script>

<template>
    <Head :title="pageTitle">
        <meta head-key="description" name="description" :content="pageDescription">
        <meta head-key="robots" name="robots" :content="robotsContent">
        <meta v-if="keywordContent" head-key="keywords" name="keywords" :content="keywordContent">
        <link head-key="canonical" rel="canonical" :href="canonicalUrl">
        <meta head-key="og-site-name" property="og:site_name" :content="clinicName">
        <meta head-key="og-type" property="og:type" content="website">
        <meta head-key="og-title" property="og:title" :content="openGraphTitle">
        <meta head-key="og-description" property="og:description" :content="openGraphDescription">
        <meta head-key="og-url" property="og:url" :content="canonicalUrl">
        <meta v-if="openGraphImage" head-key="og-image" property="og:image" :content="openGraphImage">
        <meta v-if="openGraphImageAlt" head-key="og-image-alt" property="og:image:alt" :content="openGraphImageAlt">
        <meta head-key="twitter-card" name="twitter:card" :content="twitterCard">
        <meta head-key="twitter-title" name="twitter:title" :content="twitterTitle">
        <meta head-key="twitter-description" name="twitter:description" :content="twitterDescription">
        <meta v-if="twitterImage" head-key="twitter-image" name="twitter:image" :content="twitterImage">
        <component
            :is="'script'"
            v-if="structuredDataJson"
            head-key="about-json-ld"
            type="application/ld+json"
            v-html="structuredDataJson"
        ></component>
    </Head>

    <AppLayout>
        <Masthead :content="aboutPage.masthead" />
        <Figures :figures="aboutPage.figures" />
        <FoundersNote :content="aboutPage.note" />
        <Values :content="aboutPage.values" />
        <Team :content="aboutPage.team" />
        <Cta :content="aboutPage.cta" />
    </AppLayout>
</template>
