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
    eyebrow: 'The team',
    heading: 'Small enough to know ',
    heading_accent: 'your name.',
    heading_suffix: '',
    lede: 'Five clinicians and a front desk that remembers what you were anxious about last time. You will see the same faces on every visit — that is the whole point.',
    image: '/assets/team.jpg',
    image_alt: "Two of the clinic's dentists in a treatment room",
    caption: 'Turner House, Linking Road — four surgeries, one sterilisation bay, no waiting-room queue.',
};

const fallbackClinicians = [
    { name: 'Dr. Pushpa Patel', role: 'Founder · Prosthodontics & smile design' },
    { name: 'Dr. Aditya Rao', role: 'Oral implantology & jaw joint (TMD)' },
    { name: 'Dr. Sana Merchant', role: 'Orthodontics & clear aligners' },
    { name: 'Dr. Nikhil Bhat', role: 'Endodontics & root canal therapy' },
    { name: 'Dr. Ira Kulkarni', role: 'Paediatric dentistry' },
];

const fallbackChips = [
    { text: 'Dental Council of India' },
    { text: 'ICOI fellowship' },
    { text: 'Invisalign certified' },
    { text: 'CBCT on site' },
    { text: 'Class B sterilisation' },
];

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
}));

const clinicians = computed(() => props.content?.clinicians?.length ? props.content.clinicians : fallbackClinicians);
const chips = computed(() => props.content?.chips?.length ? props.content.chips : fallbackChips);
</script>

<template>
    <section class="sec ab-team" id="team" ref="root">
        <div class="wrap">

            <div class="ab-head">
                <div data-rv>
                    <span class="eyebrow">{{ pageContent.eyebrow }}</span>
                    <h2 class="dis">
                        {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>{{ pageContent.heading_suffix }}
                    </h2>
                </div>
                <RichText class="lede ab-rich" :html="pageContent.lede" data-rv style="--d:.08s" />
            </div>

            <div class="ab-team-in">

                <figure class="ab-team-shot" data-rv style="margin:0">
                    <img :src="pageContent.image" :alt="pageContent.image_alt" loading="lazy" width="1133" height="1700">
                    <figcaption v-if="pageContent.caption">{{ pageContent.caption }}</figcaption>
                </figure>

                <div data-rv style="--d:.1s">
                    <ul class="ab-roster">
                        <li v-for="clinician in clinicians" :key="clinician.name">
                            <b>{{ clinician.name }}</b><span>{{ clinician.role }}</span>
                        </li>
                    </ul>

                    <ul class="ab-chips" aria-label="Registrations and equipment">
                        <li v-for="chip in chips" :key="chip.text">{{ chip.text }}</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</template>
