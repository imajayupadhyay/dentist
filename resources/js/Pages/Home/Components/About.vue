<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

const stats = [
    { value: '16', label: 'Years in practice' },
    { value: '12,400+', label: 'Treatments done' },
    { value: '4.9★', label: '860 reviews' },
    { value: '90 min', label: 'Per appointment' },
];
</script>

<template>
    <section class="intro" id="about" ref="root">
        <div class="wrap">
            <div class="intro-in">
                <div data-rv>
                    <span class="eyebrow">About the clinic</span>
                    <h2 class="dis">Unhurried dentistry in <em>Bandra West.</em></h2>
                </div>
                <div data-rv style="--d:.08s">
                    <p>Sixteen years on Linking Road, built around one observation: people don't dread the dentist, they dread being rushed. Ninety-minute appointments, one clinician from first scan to final polish, and a written plan before anything begins.</p>
                    <a class="btn btn-ghost" href="#treatments">See what we do
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>

            <div class="intro-stats" data-rv style="--d:.14s">
                <div v-for="stat in stats" :key="stat.label">
                    <b>{{ stat.value }}</b><span>{{ stat.label }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
