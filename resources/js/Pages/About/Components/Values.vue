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
    eyebrow: 'How we work',
    heading: 'Four things we ',
    heading_accent: 'never',
    heading_suffix: ' rush.',
    lede: 'None of this is complicated. It is simply what a dental visit looks like when the diary is built around the patient rather than the other way round.',
};

const fallbackValues = [
    {
        num: '01',
        title: 'Unhurried by design',
        copy: 'Ninety minutes per appointment. Long enough to explain everything, and long enough to stop if you need a minute.',
    },
    {
        num: '02',
        title: 'One clinician, start to finish',
        copy: 'The dentist who plans your treatment is the one who carries it out. No handovers halfway through a course.',
        delay: '.07s',
    },
    {
        num: '03',
        title: 'A plan before a drill',
        copy: 'Every course of treatment starts as one written page: what, why, how long it takes and exactly what it costs.',
        delay: '.14s',
    },
    {
        num: '04',
        title: 'Comfort is a technique',
        copy: 'Topical before the needle, computer-controlled anaesthesia, and a raised hand that stops everything at once.',
        delay: '.21s',
    },
];

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));

const values = computed(() => props.content?.items?.length ? props.content.items : fallbackValues);

function delayFor(index) {
    return index === 0 ? null : { '--d': `${(index * 0.07).toFixed(2)}s` };
}
</script>

<template>
    <section class="sec ab-values" id="values" ref="root">
        <div class="wrap">

            <div class="ab-head">
                <div data-rv>
                    <span class="eyebrow">{{ pageContent.eyebrow }}</span>
                    <h2 class="dis">
                        {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>{{ pageContent.heading_suffix }}
                    </h2>
                </div>
                <RichText class="lede ab-rich" :html="pageContent.lede" data-rv style="--d:.08s" />
            </div>

            <div class="ab-values-grid">
                <article
                    v-for="(value, index) in values"
                    :key="`${value.num}-${index}`"
                    class="ab-val"
                    data-rv
                    :style="value.delay ? { '--d': value.delay } : delayFor(index)"
                >
                    <span class="n" aria-hidden="true">{{ value.num }}</span>
                    <h3>{{ value.title }}</h3>
                    <RichText class="ab-rich" :html="value.copy" />
                </article>
            </div>

        </div>
    </section>
</template>
