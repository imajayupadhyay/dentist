<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

const clinicians = [
    { name: 'Dr. Pushpa Patel', role: 'Founder · Prosthodontics & smile design' },
    { name: 'Dr. Aditya Rao', role: 'Oral implantology & jaw joint (TMD)' },
    { name: 'Dr. Sana Merchant', role: 'Orthodontics & clear aligners' },
    { name: 'Dr. Nikhil Bhat', role: 'Endodontics & root canal therapy' },
    { name: 'Dr. Ira Kulkarni', role: 'Paediatric dentistry' },
];

const chips = [
    'Dental Council of India',
    'ICOI fellowship',
    'Invisalign certified',
    'CBCT on site',
    'Class B sterilisation',
];
</script>

<template>
    <section class="sec ab-team" id="team" ref="root">
        <div class="wrap">

            <div class="ab-head">
                <div data-rv>
                    <span class="eyebrow">The team</span>
                    <h2 class="dis">Small enough to know <em>your name.</em></h2>
                </div>
                <p class="lede" data-rv style="--d:.08s">Five clinicians and a front desk that remembers what you were anxious about last time. You will see the same faces on every visit &mdash; that is the whole point.</p>
            </div>

            <div class="ab-team-in">

                <figure class="ab-team-shot" data-rv style="margin:0">
                    <img src="/assets/team.jpg" alt="Two of the clinic's dentists in a treatment room" loading="lazy" width="1133" height="1700">
                    <figcaption>Turner House, Linking Road &mdash; four surgeries, one sterilisation bay, no waiting-room queue.</figcaption>
                </figure>

                <div data-rv style="--d:.1s">
                    <ul class="ab-roster">
                        <li v-for="clinician in clinicians" :key="clinician.name">
                            <b>{{ clinician.name }}</b><span>{{ clinician.role }}</span>
                        </li>
                    </ul>

                    <ul class="ab-chips" aria-label="Registrations and equipment">
                        <li v-for="chip in chips" :key="chip">{{ chip }}</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</template>
