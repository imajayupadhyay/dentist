<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);
</script>

<template>
    <section class="ab-note" id="note" aria-labelledby="note-h" ref="root">
        <div class="ab-note-in">

            <div class="ab-note-media">
                <img src="/assets/doctor-portrait.jpg" alt="Dr. Pushpa Patel in the clinic corridor" loading="lazy" width="957" height="1700">
            </div>

            <div class="ab-note-body">
                <div class="inner" data-rv>
                    <span class="eyebrow" id="note-h">Founder&rsquo;s note</span>

                    <blockquote class="ab-quote dis">I trained to fix teeth. Sixteen years in, the harder part is making someone comfortable enough to let you.</blockquote>

                    <p>Almost everyone who walks in here has a story about a dentist who was in a hurry. So the practice was built around the opposite of that &mdash; ninety-minute appointments, one clinician from the first scan to the final polish, and a written plan with a price on it before anything begins.</p>

                    <div class="ab-sign">
                        <span class="mark" aria-hidden="true">P. Patel</span>
                        <span class="who">
                            <b>Dr. Pushpa Patel</b>
                            <span>BDS, MDS (Prosthodontics) &middot; Founder &amp; Principal Dentist</span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>
