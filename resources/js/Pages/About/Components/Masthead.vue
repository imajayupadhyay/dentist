<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);
const lead = ref(null);
const inset = ref(null);

useScrollReveal(root);

const meta = [
    'Bandra West, Mumbai',
    'Established 2009',
    'Five clinicians',
];

let cleanupParallax = null;

onMounted(() => {
    const hero = root.value;
    const leadShot = lead.value;
    const insetShot = inset.value;

    if (
        !hero ||
        !leadShot ||
        !insetShot ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
        !window.matchMedia('(min-width: 861px)').matches
    ) {
        return;
    }

    let ticking = false;

    function paint() {
        ticking = false;

        const rect = hero.getBoundingClientRect();
        const progress = Math.min(Math.max(-rect.top / (rect.height || 1), 0), 1);

        leadShot.style.transform = `translate3d(0,${(progress * -34).toFixed(2)}px,0)`;
        insetShot.style.transform = `translate3d(0,${(progress * -62).toFixed(2)}px,0)`;
    }

    function requestPaint() {
        if (ticking) {
            return;
        }

        ticking = true;
        requestAnimationFrame(paint);
    }

    paint();
    window.addEventListener('scroll', requestPaint, { passive: true });
    window.addEventListener('resize', requestPaint);

    cleanupParallax = () => {
        window.removeEventListener('scroll', requestPaint);
        window.removeEventListener('resize', requestPaint);
        leadShot.style.transform = '';
        insetShot.style.transform = '';
    };
});

onBeforeUnmount(() => {
    cleanupParallax?.();
    cleanupParallax = null;
});
</script>

<template>
    <section class="ab-hero" id="ab-hero" ref="root">
        <div class="wrap">
            <div class="ab-hero-in">

                <div class="ab-hero-copy">
                    <span class="eyebrow" data-rv>About the clinic</span>
                    <h1 class="dis" data-rv style="--d:.06s">Sixteen years of <em>unhurried</em> dentistry.</h1>
                    <p class="lede" data-rv style="--d:.12s">We opened on Linking Road in 2009 with one rule that has not changed since: nobody leaves this chair unsure about what was done, why it was done, or what it cost.</p>

                    <div class="ab-meta" data-rv style="--d:.18s">
                        <span v-for="item in meta" :key="item">{{ item }}</span>
                    </div>

                    <div class="ab-hero-cta" data-rv style="--d:.24s">
                        <a class="btn btn-brand" href="/#book">Book an appointment
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a class="btn btn-ghost" href="#team">Meet the team</a>
                    </div>
                </div>

                <div class="ab-stack" data-rv style="--d:.14s">
                    <div class="ab-shot lead" ref="lead">
                        <img src="/assets/clinic-wide.jpg" alt="A dentist at work in one of the clinic's treatment rooms" fetchpriority="high" width="1135" height="1700">
                    </div>

                    <div class="ab-shot inset" ref="inset">
                        <img src="/assets/clinic-suite.jpg" alt="A treatment chair and overhead light in the clinic" loading="lazy" width="1700" height="1133">
                    </div>

                    <div class="ab-chip">
                        <span class="stars" aria-hidden="true">
                            <svg v-for="star in 5" :key="star" viewBox="0 0 24 24"><path d="M12 2l3 6.5 7 .9-5 4.9 1.2 7L12 18l-6.2 3.3L7 14.3 2 9.4l7-.9z"/></svg>
                        </span>
                        <span>
                            <b>4.9 out of 5</b>
                            <small>860 Google reviews</small>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
