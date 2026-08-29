<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const props = defineProps({
    figures: {
        type: Array,
        default: () => [],
    },
});

const root = ref(null);

useScrollReveal(root);

const fallbackFigures = [
    { count: '16', suffix: '', value: '16', label: 'Years in practice' },
    { count: '12400', suffix: '+', value: '12,400+', label: 'Treatments done' },
    { count: '4.9', decimals: '1', suffix: '★', value: '4.9★', label: '860 reviews' },
    { count: '90', suffix: ' min', value: '90 min', label: 'Per appointment' },
];

const visibleFigures = computed(() => props.figures?.length ? props.figures : fallbackFigures);

let observer = null;
let running = true;
const animationFrames = new Set();
const timers = new Set();

function formatCount(value, decimals) {
    return decimals > 0
        ? value.toFixed(decimals)
        : Math.round(value).toLocaleString('en-US');
}

function runCounter(el) {
    const target = parseFloat(el.getAttribute('data-count'));
    const decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
    const suffix = el.getAttribute('data-suffix') || '';
    const prefix = el.getAttribute('data-prefix') || '';
    const final = el.textContent;

    if (Number.isNaN(target)) {
        return;
    }

    const duration = 1500;
    let start = null;
    let settled = false;

    function settle() {
        if (settled) {
            return;
        }

        settled = true;
        el.textContent = final;
    }

    function frame(now) {
        if (!running || settled) {
            return;
        }

        if (start === null) {
            start = now;
        }

        const t = Math.min((now - start) / duration, 1);

        if (t >= 1) {
            settle();
            return;
        }

        const eased = 1 - ((1 - t) ** 3);
        el.textContent = prefix + formatCount(target * eased, decimals) + suffix;

        const frameId = requestAnimationFrame(frame);
        animationFrames.add(frameId);
    }

    const firstFrame = requestAnimationFrame(frame);
    const timeout = setTimeout(settle, duration + 400);

    animationFrames.add(firstFrame);
    timers.add(timeout);
}

onMounted(() => {
    const el = root.value;

    if (
        !el ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
        typeof IntersectionObserver === 'undefined'
    ) {
        return;
    }

    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                observer.unobserve(entry.target);
                runCounter(entry.target);
            });
        },
        { threshold: 0.6 },
    );

    el.querySelectorAll('[data-count]').forEach((target) => observer.observe(target));
});

onBeforeUnmount(() => {
    running = false;
    observer?.disconnect();
    observer = null;
    animationFrames.forEach((frameId) => cancelAnimationFrame(frameId));
    timers.forEach((timer) => clearTimeout(timer));
    animationFrames.clear();
    timers.clear();
});
</script>

<template>
    <section class="ab-figures" aria-label="The clinic in numbers" ref="root">
        <div class="wrap">
            <div class="ab-figures-grid" data-rv>
                <div v-for="figure in visibleFigures" :key="figure.label">
                    <b
                        :data-count="figure.count"
                        :data-decimals="figure.decimals ?? null"
                        :data-suffix="figure.suffix"
                        :data-prefix="figure.prefix"
                    >{{ figure.value }}</b><span>{{ figure.label }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
