<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({}),
    },
});

const root = ref(null);
const rail = ref(null);

useScrollReveal(root);

const fallbackContent = {
    eyebrow: 'Patient stories',
    heading: 'Real stories, ',
    heading_accent: 'real smiles.',
};

const fallbackStories = [
    { src: '/assets/video/story-1.mp4', poster: '/assets/portrait-warm.jpg', name: 'Priya Nair', tag: 'Sample' },
    { src: '/assets/video/story-2.mp4', poster: '/assets/bw-smile.jpg', name: 'Rakesh Menon', tag: 'Sample' },
    { src: '/assets/video/story-3.mp4', poster: '/assets/whitening.jpg', name: 'Meera Iyer', tag: 'Sample' },
    { src: '/assets/video/story-4.mp4', poster: '/assets/hero-smile.jpg', name: 'Anand Sharma', tag: 'Sample' },
];

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));

const stories = computed(() => props.content?.items?.length ? props.content.items : fallbackStories);

const videos = ref([]);
const playing = ref(null);
const atStart = ref(true);
const atEnd = ref(false);

let reduceMotion = false;
let observer = null;

function step() {
    const el = rail.value;
    const card = el?.querySelector('.vcard');

    if (!card) {
        return 300;
    }

    return card.getBoundingClientRect().width + parseFloat(getComputedStyle(el).gap || 0);
}

function sync() {
    const el = rail.value;

    if (!el) {
        return;
    }

    const max = el.scrollWidth - el.clientWidth;

    atStart.value = el.scrollLeft < 6;
    atEnd.value = el.scrollLeft > max - 6;
}

function scroll(direction) {
    rail.value?.scrollBy({
        left: direction * step(),
        behavior: reduceMotion ? 'auto' : 'smooth',
    });
}

function stopAll() {
    videos.value.forEach((video) => {
        if (!video) {
            return;
        }

        video.pause();
        video.controls = false;
    });

    playing.value = null;
}

function play(index) {
    // Only one story plays at a time.
    videos.value.forEach((video, i) => {
        if (video && i !== index) {
            video.pause();
            video.controls = false;
        }
    });

    const video = videos.value[index];

    if (!video) {
        return;
    }

    video.muted = false;
    video.controls = true;
    playing.value = index;

    const attempt = video.play();

    if (attempt?.catch) {
        attempt.catch(() => {
            // Autoplay policy refused unmuted playback — fall back to muted.
            video.muted = true;
            video.play();
        });
    }
}

function onEnded(index) {
    const video = videos.value[index];

    if (video) {
        video.controls = false;
        video.currentTime = 0;
    }

    if (playing.value === index) {
        playing.value = null;
    }
}

onMounted(() => {
    reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    sync();
    window.addEventListener('resize', sync);

    // Stop playback when the section scrolls away.
    if (root.value && typeof IntersectionObserver !== 'undefined') {
        observer = new IntersectionObserver(
            (entries) => entries.forEach((entry) => !entry.isIntersecting && stopAll()),
            { threshold: 0.05 },
        );

        observer.observe(root.value);
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', sync);
    observer?.disconnect();
    stopAll();
});
</script>

<template>
    <section class="sec stories" id="stories" ref="root">
        <div class="wrap">
            <div class="stories-head">
                <span class="eyebrow" data-rv>{{ pageContent.eyebrow }}</span>
                <h2 class="dis" data-rv style="--d:.06s">
                    {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>
                </h2>
            </div>

            <div class="vwrap" data-rv style="--d:.12s">
                <button
                    class="vnav prev"
                    aria-label="Previous stories"
                    :disabled="atStart"
                    @click="scroll(-1)"
                >
                    <svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                </button>

                <div class="vrail" ref="rail" @scroll.passive="sync">
                    <article
                        v-for="(story, index) in stories"
                        :key="`${story.src}-${index}`"
                        class="vcard"
                        :class="{ playing: playing === index }"
                    >
                        <div class="vframe">
                            <video
                                :ref="(el) => (videos[index] = el)"
                                :src="story.src"
                                :poster="story.poster"
                                preload="metadata"
                                playsinline
                                muted
                                @ended="onEnded(index)"
                            ></video>
                            <span v-if="story.tag" class="vtag">{{ story.tag }}</span>
                            <button
                                class="vover"
                                :aria-label="`Play ${story.name}'s story`"
                                @click="play(index)"
                            >
                                <span class="vplay">
                                    <svg viewBox="0 0 24 24"><path d="M8 5.2v13.6L19 12z"/></svg>
                                </span>
                            </button>
                        </div>
                    </article>
                </div>

                <button
                    class="vnav next"
                    aria-label="More stories"
                    :disabled="atEnd"
                    @click="scroll(1)"
                >
                    <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </div>
        </div>
    </section>
</template>
