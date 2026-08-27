<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

const values = [
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
</script>

<template>
    <section class="sec ab-values" id="values" ref="root">
        <div class="wrap">

            <div class="ab-head">
                <div data-rv>
                    <span class="eyebrow">How we work</span>
                    <h2 class="dis">Four things we <em>never</em> rush.</h2>
                </div>
                <p class="lede" data-rv style="--d:.08s">None of this is complicated. It is simply what a dental visit looks like when the diary is built around the patient rather than the other way round.</p>
            </div>

            <div class="ab-values-grid">
                <article
                    v-for="value in values"
                    :key="value.num"
                    class="ab-val"
                    data-rv
                    :style="value.delay ? { '--d': value.delay } : null"
                >
                    <span class="n" aria-hidden="true">{{ value.num }}</span>
                    <h3>{{ value.title }}</h3>
                    <p>{{ value.copy }}</p>
                </article>
            </div>

        </div>
    </section>
</template>
