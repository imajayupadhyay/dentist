<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);
</script>

<template>
    <section class="ab-cta" aria-labelledby="cta-h" ref="root">
        <div class="wrap">
            <div class="ab-cta-in">
                <div data-rv>
                    <h2 class="dis" id="cta-h">Come and see the place <em>first.</em></h2>
                    <p>A first visit here is a conversation and a set of scans. Nothing is treated, and nothing is decided, until you have the plan in your hand.</p>
                </div>
                <div class="ab-cta-btns" data-rv style="--d:.1s">
                    <a class="btn btn-white" href="/#book">Book an appointment
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a class="btn btn-line" href="tel:+912226000000">Call the clinic</a>
                </div>
            </div>
        </div>
    </section>
</template>
