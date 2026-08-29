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
    eyebrow: "Founder's note",
    image: '/assets/doctor-portrait.jpg',
    image_alt: 'Dr. Pushpa Patel in the clinic corridor',
    quote: 'I trained to fix teeth. Sixteen years in, the harder part is making someone comfortable enough to let you.',
    body: 'Almost everyone who walks in here has a story about a dentist who was in a hurry. So the practice was built around the opposite of that — ninety-minute appointments, one clinician from the first scan to the final polish, and a written plan with a price on it before anything begins.',
    signature: 'P. Patel',
    name: 'Dr. Pushpa Patel',
    role: 'BDS, MDS (Prosthodontics) · Founder & Principal Dentist',
};

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));
</script>

<template>
    <section class="ab-note" id="note" aria-labelledby="note-h" ref="root">
        <div class="ab-note-in">

            <div class="ab-note-media">
                <img :src="pageContent.image" :alt="pageContent.image_alt" loading="lazy" width="957" height="1700">
            </div>

            <div class="ab-note-body">
                <div class="inner" data-rv>
                    <span class="eyebrow" id="note-h">{{ pageContent.eyebrow }}</span>

                    <blockquote class="ab-quote dis">{{ pageContent.quote }}</blockquote>

                    <RichText class="ab-rich" :html="pageContent.body" />

                    <div class="ab-sign">
                        <span class="mark" aria-hidden="true">{{ pageContent.signature }}</span>
                        <span class="who">
                            <b>{{ pageContent.name }}</b>
                            <span>{{ pageContent.role }}</span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>
