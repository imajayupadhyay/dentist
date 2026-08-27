<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const DURATION = 6500;

const slides = [
    {
        eyebrow: 'Bandra West · Mumbai',
        heading: 'Your best smile, ',
        em: 'by design.',
        copy: 'Digital smile design, ceramic artistry and ninety-minute appointments — so nothing is rushed, and nothing is missed.',
        secondary: { label: 'See treatments', href: '#treatments' },
        image: '/assets/hero-smile.jpg',
        dot: 'Smile design',
    },
    {
        eyebrow: 'Comfort first',
        heading: "Dentistry that ",
        em: "doesn't hurt.",
        copy: 'Numbing before the needle, computer-controlled delivery, and a hand signal that stops everything the moment you raise it.',
        secondary: { label: 'How we work', href: '#about' },
        image: '/assets/smile-closeup.jpg',
        dot: 'Painless care',
    },
    {
        eyebrow: 'All under one roof',
        heading: 'Implants, aligners, ',
        em: 'everything.',
        copy: 'From same-day emergencies to full-mouth rehabilitation — planned, placed and finished in-house by the same clinician.',
        secondary: { label: 'Browse all', href: '#treatments' },
        image: '/assets/clinic-suite.jpg',
        dot: 'Full service',
    },
];

const trust = [
    { value: '16', label: 'Years in practice' },
    { value: '12,400+', label: 'Treatments done' },
    { value: '4.9★', label: '860 Google reviews' },
];

const hero = ref(null);
const active = ref(0);
const paused = ref(false);

/** Bumped on every change so the dot's progress-fill animation restarts from zero. */
const cycle = ref(0);

let timer = null;
let reduceMotion = false;
let touchX = null;

function go(next) {
    active.value = (next + slides.length) % slides.length;
    cycle.value += 1;
    restart();
}

function restart() {
    clearInterval(timer);

    if (reduceMotion) {
        return;
    }

    timer = setInterval(() => {
        if (document.hidden || paused.value) {
            return;
        }

        go(active.value + 1);
    }, DURATION);
}

function onKeydown(event) {
    if (event.key === 'ArrowRight') {
        event.preventDefault();
        go(active.value + 1);
    }

    if (event.key === 'ArrowLeft') {
        event.preventDefault();
        go(active.value - 1);
    }
}

function onTouchStart(event) {
    touchX = event.touches[0].clientX;
}

function onTouchEnd(event) {
    if (touchX === null) {
        return;
    }

    const dx = event.changedTouches[0].clientX - touchX;

    if (Math.abs(dx) > 45) {
        go(dx < 0 ? active.value + 1 : active.value - 1);
    }

    touchX = null;
}

onMounted(() => {
    reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    restart();
});

onBeforeUnmount(() => clearInterval(timer));
</script>

<template>
    <section
        class="hero"
        id="hero"
        ref="hero"
        :class="{ paused }"
        aria-roledescription="carousel"
        aria-label="Clinic highlights"
        @pointerenter="paused = true"
        @pointerleave="paused = false"
        @keydown="onKeydown"
        @touchstart.passive="onTouchStart"
        @touchend.passive="onTouchEnd"
    >
        <!-- full-bleed slide images -->
        <div class="frame" aria-hidden="true">
            <figure
                v-for="(slide, index) in slides"
                :key="slide.image"
                :class="{ on: index === active }"
            >
                <img
                    :src="slide.image"
                    alt=""
                    :fetchpriority="index === 0 ? 'high' : null"
                    :loading="index === 0 ? null : 'lazy'"
                >
            </figure>
        </div>
        <div class="hero-scrim" aria-hidden="true"></div>

        <div class="wrap">
            <div class="hero-copy" aria-live="polite">
                <article
                    v-for="(slide, index) in slides"
                    :key="slide.dot"
                    class="slide"
                    :class="{ on: index === active }"
                    role="group"
                    aria-roledescription="slide"
                    :aria-label="`${index + 1} of ${slides.length}`"
                >
                    <span class="eyebrow">{{ slide.eyebrow }}</span>
                    <h1 class="dis">{{ slide.heading }}<em>{{ slide.em }}</em></h1>
                    <p>{{ slide.copy }}</p>
                    <div class="hero-cta">
                        <a class="btn btn-brand" href="#book">Book appointment
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a class="btn btn-ghost" :href="slide.secondary.href">{{ slide.secondary.label }}</a>
                    </div>
                </article>
            </div>

            <div class="trust">
                <template v-for="(item, index) in trust" :key="item.label">
                    <span v-if="index" class="sep" aria-hidden="true"></span>
                    <div class="t"><b>{{ item.value }}</b><span>{{ item.label }}</span></div>
                </template>
            </div>

            <div class="hero-ctl">
                <div class="dots" role="tablist" aria-label="Choose slide">
                    <button
                        v-for="(slide, index) in slides"
                        :key="slide.dot"
                        class="dot"
                        :class="{ on: index === active }"
                        role="tab"
                        :aria-selected="index === active ? 'true' : 'false'"
                        @click="go(index)"
                    >
                        <i :key="`${index}-${cycle}`"></i><b>{{ String(index + 1).padStart(2, '0') }}</b><span> — {{ slide.dot }}</span>
                    </button>
                </div>
                <div class="arrows">
                    <button aria-label="Previous slide" @click="go(active - 1)">
                        <svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                    </button>
                    <button aria-label="Next slide" @click="go(active + 1)">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
