<script setup>
import { computed, ref } from 'vue';
import RichText from '@/Components/Global/RichText.vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({}),
    },
    stats: {
        type: Array,
        default: () => [],
    },
});

const root = ref(null);

useScrollReveal(root);

const fallbackContent = {
    eyebrow: 'About the clinic',
    heading: 'Unhurried dentistry in ',
    heading_accent: 'Bandra West.',
    body: "Sixteen years on Linking Road, built around one observation: people don't dread the dentist, they dread being rushed. Ninety-minute appointments, one clinician from first scan to final polish, and a written plan before anything begins.",
    cta_label: 'See what we do',
    cta_href: '#treatments',
};

const fallbackStats = [
    { value: '16', label: 'Years in practice' },
    { value: '12,400+', label: 'Treatments done' },
    { value: '4.9★', label: '860 reviews' },
    { value: '90 min', label: 'Per appointment' },
];

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));

const visibleStats = computed(() => props.stats?.length ? props.stats : fallbackStats);
</script>

<template>
    <section class="intro" id="about" ref="root">
        <div class="wrap">
            <div class="intro-in">
                <div data-rv>
                    <span class="eyebrow">{{ pageContent.eyebrow }}</span>
                    <h2 class="dis">
                        {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>
                    </h2>
                </div>
                <div data-rv style="--d:.08s">
                    <RichText class="intro-rich home-rich" :html="pageContent.body" />
                    <a class="btn btn-ghost" :href="pageContent.cta_href">{{ pageContent.cta_label }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>

            <div class="intro-stats" data-rv style="--d:.14s">
                <div v-for="stat in visibleStats" :key="stat.label">
                    <b>{{ stat.value }}</b><span>{{ stat.label }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
