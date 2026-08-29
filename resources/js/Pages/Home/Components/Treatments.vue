<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

defineProps({
    treatments: {
        type: Array,
        default: () => [],
    },
});

const root = ref(null);

useScrollReveal(root);
</script>

<template>
    <section id="treatments" ref="root">
        <Link
            v-for="(item, index) in treatments"
            :key="item.slug"
            class="tband"
            :class="{ flip: index % 2 === 1 }"
            :href="item.href"
            :style="item.theme"
        >
            <div class="tband-in">
                <div class="tband-media">
                    <img :src="item.image" :alt="item.alt" loading="lazy">
                </div>
                <div class="tband-body">
                    <div class="inner" data-rv>
                        <span class="tnum">{{ item.num }}</span>
                        <h3>{{ item.title }}</h3>
                        <p class="tsub">{{ item.sub }}</p>
                        <div class="tdesc">
                            <span class="ticon"><svg viewBox="0 0 24 24" v-html="item.icon"></svg></span>
                            <p>{{ item.copy }}</p>
                        </div>
                        <span class="tmore">Read more
                            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </Link>
    </section>
</template>
