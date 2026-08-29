<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    sections: {
        type: Array,
        default: () => [],
    },
});

const rail = ref(null);
const current = ref(props.sections[0]?.id ?? null);

let observer = null;
let reducedMotion = false;

onMounted(() => {
    reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const targets = props.sections
        .map((section) => document.getElementById(section.id))
        .filter(Boolean);

    if (! targets.length) {
        return;
    }

    if ('IntersectionObserver' in window) {
        const visible = new Map();

        observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                visible.set(entry.target.id, entry.isIntersecting);
            });

            const active = targets.find((section) => visible.get(section.id));

            if (active) {
                setCurrent(active.id);
            }
        }, { rootMargin: '-32% 0px -55% 0px' });

        targets.forEach((section) => observer.observe(section));
    }
});

onBeforeUnmount(() => {
    observer?.disconnect();
    observer = null;
});

function setCurrent(id) {
    if (current.value === id) {
        return;
    }

    current.value = id;
    scrollActiveIntoView();
}

function scrollActiveIntoView() {
    nextTick(() => {
        const railEl = rail.value;
        const active = railEl?.querySelector(`[data-target="${current.value}"]`);

        if (! railEl || ! active) {
            return;
        }

        const railBox = railEl.getBoundingClientRect();
        const pill = active.getBoundingClientRect();

        if (pill.left < railBox.left || pill.right > railBox.right) {
            railEl.scrollTo({
                left: active.offsetLeft - railEl.clientWidth / 2 + active.offsetWidth / 2,
                behavior: reducedMotion ? 'auto' : 'smooth',
            });
        }
    });
}
</script>

<template>
    <div v-if="sections.length" class="tx-nav">
        <div ref="rail" class="tx-nav-in wrap">
            <a
                v-for="section in sections"
                :key="section.id"
                :href="`#${section.id}`"
                :data-target="section.id"
                :class="{ on: current === section.id }"
                :aria-current="current === section.id ? 'true' : null"
                @click="setCurrent(section.id)"
            >{{ section.label }}</a>
        </div>
    </div>
</template>
