<script setup>
import { computed, ref } from 'vue';
import RichText from '@/Components/Global/RichText.vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({}),
    },
});

const root = ref(null);

useScrollReveal(root);

const fallbackContent = {
    heading: 'Come and see the place ',
    heading_accent: 'first.',
    heading_suffix: '',
    body: 'A first visit here is a conversation and a set of scans. Nothing is treated, and nothing is decided, until you have the plan in your hand.',
    primary_label: 'Book an appointment',
    primary_href: '/#book',
    secondary_label: 'Call the clinic',
    secondary_href: 'tel:+912226000000',
};

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));
</script>

<template>
    <section class="ab-cta" aria-labelledby="cta-h" ref="root">
        <div class="wrap">
            <div class="ab-cta-in">
                <div data-rv>
                    <h2 class="dis" id="cta-h">
                        {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>{{ pageContent.heading_suffix }}
                    </h2>
                    <RichText class="ab-rich" :html="pageContent.body" />
                </div>
                <div class="ab-cta-btns" data-rv style="--d:.1s">
                    <a class="btn btn-white" :href="pageContent.primary_href">{{ pageContent.primary_label }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a
                        v-if="pageContent.secondary_label && pageContent.secondary_href"
                        class="btn btn-line"
                        :href="pageContent.secondary_href"
                    >{{ pageContent.secondary_label }}</a>
                </div>
            </div>
        </div>
    </section>
</template>
