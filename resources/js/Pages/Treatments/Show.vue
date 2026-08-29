<script setup>
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';
import TreatmentCta from './Components/TreatmentCta.vue';
import TreatmentFaq from './Components/TreatmentFaq.vue';
import TreatmentHero from './Components/TreatmentHero.vue';
import TreatmentOverview from './Components/TreatmentOverview.vue';
import TreatmentProcess from './Components/TreatmentProcess.vue';
import TreatmentRelated from './Components/TreatmentRelated.vue';
import TreatmentSectionNav from './Components/TreatmentSectionNav.vue';
import TreatmentSuitability from './Components/TreatmentSuitability.vue';

const props = defineProps({
    treatment: {
        type: Object,
        required: true,
    },
    relatedTreatments: {
        type: Array,
        default: () => [],
    },
});

const root = ref(null);
const clinicName = "Dr. Pushpa Patel's Dental Clinic";
const clinicPhone = '+912226000000';
const clinicAddress = {
    '@type': 'PostalAddress',
    streetAddress: '2nd Floor, Turner House, Linking Road, Bandra West',
    addressLocality: 'Mumbai',
    postalCode: '400050',
    addressCountry: 'IN',
};

useScrollReveal(root);

const seoMeta = computed(() => props.treatment.seo_meta || {});
const pageTitle = computed(() => seoMeta.value.title || props.treatment.seo_title || props.treatment.title);
const pageDescription = computed(() => seoMeta.value.description || props.treatment.seo_description || stripHtml(props.treatment.summary));
const pagePath = computed(() => `/treatments/${props.treatment.slug}`);
const clinicUrl = computed(() => absoluteUrl('/'));
const canonicalUrl = computed(() => seoMeta.value.canonical || props.treatment.seo_canonical_url || props.treatment.public_url || absoluteUrl(pagePath.value));
const robotsContent = computed(() => seoMeta.value.robots || [
    props.treatment.seo_robots_index === false ? 'noindex' : 'index',
    props.treatment.seo_robots_follow === false ? 'nofollow' : 'follow',
    'max-image-preview:large',
    'max-snippet:-1',
    'max-video-preview:-1',
].join(','));
const keywordContent = computed(() => seoMeta.value.keywords || [
    props.treatment.seo_focus_keyword,
    props.treatment.seo_secondary_keywords,
].filter(Boolean).join(', '));
const openGraphTitle = computed(() => seoMeta.value.og?.title || props.treatment.seo_og_title || pageTitle.value);
const openGraphDescription = computed(() => seoMeta.value.og?.description || props.treatment.seo_og_description || pageDescription.value);
const openGraphImage = computed(() => seoMeta.value.og?.image || absoluteUrl(props.treatment.seo_og_image || props.treatment.hero_image || props.treatment.home_image));
const openGraphImageAlt = computed(() => seoMeta.value.og?.image_alt || props.treatment.seo_og_image_alt || props.treatment.hero_image_alt || props.treatment.home_image_alt || openGraphTitle.value);
const twitterTitle = computed(() => seoMeta.value.twitter?.title || props.treatment.seo_twitter_title || openGraphTitle.value);
const twitterDescription = computed(() => seoMeta.value.twitter?.description || props.treatment.seo_twitter_description || openGraphDescription.value);
const twitterImage = computed(() => seoMeta.value.twitter?.image || absoluteUrl(props.treatment.seo_twitter_image || props.treatment.seo_og_image || props.treatment.hero_image || props.treatment.home_image));
const twitterCard = computed(() => seoMeta.value.twitter?.card || props.treatment.seo_twitter_card || 'summary_large_image');
const breadcrumbLabel = computed(() => props.treatment.seo_breadcrumb_label || props.treatment.home_title || props.treatment.title);
const schemaType = computed(() => props.treatment.seo_schema_type || 'MedicalProcedure');
const schemaName = computed(() => props.treatment.seo_schema_name || props.treatment.title || props.treatment.home_title);
const schemaDescription = computed(() => props.treatment.seo_schema_description || pageDescription.value);
const structuredDataJson = computed(() => {
    if (seoMeta.value.json_ld) {
        return seoMeta.value.json_ld;
    }

    if (! props.treatment.seo_enable_schema) {
        return '';
    }

    const faqItems = Array.isArray(props.treatment.faqs)
        ? props.treatment.faqs
            .filter((faq) => faq.question && faq.answer)
            .map((faq) => ({
                '@type': 'Question',
                name: faq.question,
                acceptedAnswer: {
                    '@type': 'Answer',
                    text: stripHtml(faq.answer),
                },
            }))
        : [];

    const graph = [
        compactObject({
            '@type': 'WebSite',
            '@id': `${clinicUrl.value}#website`,
            name: clinicName,
            url: clinicUrl.value,
        }),
        compactObject({
            '@type': 'WebPage',
            '@id': `${canonicalUrl.value}#webpage`,
            url: canonicalUrl.value,
            name: pageTitle.value,
            description: pageDescription.value,
            datePublished: props.treatment.created_at_iso,
            dateModified: props.treatment.updated_at_iso,
            isPartOf: { '@id': `${clinicUrl.value}#website` },
            primaryImageOfPage: openGraphImage.value ? { '@id': `${canonicalUrl.value}#primaryimage` } : null,
            breadcrumb: { '@id': `${canonicalUrl.value}#breadcrumb` },
        }),
        compactObject({
            '@type': 'ImageObject',
            '@id': `${canonicalUrl.value}#primaryimage`,
            url: openGraphImage.value,
            caption: openGraphImageAlt.value,
        }),
        compactObject({
            '@type': 'BreadcrumbList',
            '@id': `${canonicalUrl.value}#breadcrumb`,
            itemListElement: [
                {
                    '@type': 'ListItem',
                    position: 1,
                    name: 'Home',
                    item: clinicUrl.value,
                },
                {
                    '@type': 'ListItem',
                    position: 2,
                    name: 'Treatments',
                    item: `${clinicUrl.value}#treatments`,
                },
                {
                    '@type': 'ListItem',
                    position: 3,
                    name: breadcrumbLabel.value,
                    item: canonicalUrl.value,
                },
            ],
        }),
        compactObject({
            '@type': schemaType.value,
            '@id': `${canonicalUrl.value}#treatment`,
            name: schemaName.value,
            description: schemaDescription.value,
            image: openGraphImage.value,
            url: canonicalUrl.value,
            provider: {
                '@type': 'Dentist',
                name: clinicName,
                url: clinicUrl.value,
                telephone: clinicPhone,
                address: clinicAddress,
            },
            areaServed: {
                '@type': 'City',
                name: 'Mumbai',
            },
        }),
    ].filter((item) => item['@type'] !== 'ImageObject' || item.url);

    if (faqItems.length) {
        graph.push({
            '@type': 'FAQPage',
            '@id': `${canonicalUrl.value}#faq-schema`,
            mainEntity: faqItems,
        });
    }

    return safeJsonLd({
        '@context': 'https://schema.org',
        '@graph': graph,
    });
});

const sections = computed(() => [
    { id: 'overview', label: 'Overview', show: Boolean(props.treatment.overview_heading) },
    { id: 'suitability', label: 'Is it for you?', show: hasItems(props.treatment.suitable_for) || hasItems(props.treatment.not_suitable) },
    { id: 'process', label: 'How it works', show: hasItems(props.treatment.steps) },
    { id: 'faq', label: 'FAQs', show: hasItems(props.treatment.faqs) },
].filter((section) => section.show));

function hasItems(value) {
    return Array.isArray(value) && value.length > 0;
}

function stripHtml(value) {
    return String(value || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

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

function compactObject(object) {
    return Object.fromEntries(
        Object.entries(object).filter(([, value]) => {
            if (value === null || value === undefined || value === '') {
                return false;
            }

            return ! (Array.isArray(value) && value.length === 0);
        }),
    );
}

function safeJsonLd(value) {
    return JSON.stringify(value)
        .replace(/</g, '\\u003C')
        .replace(/>/g, '\\u003E')
        .replace(/&/g, '\\u0026')
        .replace(/\u2028/g, '\\u2028')
        .replace(/\u2029/g, '\\u2029');
}
</script>

<template>
    <Head :title="pageTitle">
        <meta head-key="description" name="description" :content="pageDescription">
        <meta head-key="robots" name="robots" :content="robotsContent">
        <meta v-if="keywordContent" head-key="keywords" name="keywords" :content="keywordContent">
        <link head-key="canonical" rel="canonical" :href="canonicalUrl">
        <meta head-key="og-site-name" property="og:site_name" :content="clinicName">
        <meta head-key="og-type" property="og:type" content="article">
        <meta head-key="og-title" property="og:title" :content="openGraphTitle">
        <meta head-key="og-description" property="og:description" :content="openGraphDescription">
        <meta head-key="og-url" property="og:url" :content="canonicalUrl">
        <meta v-if="openGraphImage" head-key="og-image" property="og:image" :content="openGraphImage">
        <meta v-if="openGraphImageAlt" head-key="og-image-alt" property="og:image:alt" :content="openGraphImageAlt">
        <meta head-key="twitter-card" name="twitter:card" :content="twitterCard">
        <meta head-key="twitter-title" name="twitter:title" :content="twitterTitle">
        <meta head-key="twitter-description" name="twitter:description" :content="twitterDescription">
        <meta v-if="twitterImage" head-key="twitter-image" name="twitter:image" :content="twitterImage">
        <meta v-if="props.treatment.updated_at_iso" head-key="article-modified-time" property="article:modified_time" :content="props.treatment.updated_at_iso">
        <component
            :is="'script'"
            v-if="structuredDataJson"
            head-key="treatment-json-ld"
            type="application/ld+json"
            v-html="structuredDataJson"
        ></component>
    </Head>

    <AppLayout>
        <div ref="root" class="tx-page" :data-tone="treatment.tone">
            <TreatmentHero :treatment="treatment" />
            <TreatmentSectionNav :sections="sections" />
            <TreatmentOverview v-if="sections.some((section) => section.id === 'overview')" :treatment="treatment" />
            <TreatmentSuitability v-if="sections.some((section) => section.id === 'suitability')" :treatment="treatment" />
            <TreatmentProcess v-if="sections.some((section) => section.id === 'process')" :treatment="treatment" />
            <TreatmentFaq v-if="sections.some((section) => section.id === 'faq')" :treatment="treatment" />
            <TreatmentRelated
                v-if="relatedTreatments.length"
                :related-treatments="relatedTreatments"
            />
            <TreatmentCta :treatment="treatment" />
        </div>
    </AppLayout>
</template>
