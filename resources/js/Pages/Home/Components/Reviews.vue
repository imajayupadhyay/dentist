<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

const reviews = [
    {
        name: 'Priya Nair',
        when: '2 weeks ago',
        avatar: '/assets/av-priya.jpg',
        text: 'I put off a broken molar for three years after a bad experience as a teenager. Dr. Patel spent the whole first appointment just talking. It was fixed on the third visit and I genuinely did not feel a thing.',
    },
    {
        name: 'Rakesh Menon',
        when: 'a month ago',
        avatar: '/assets/av-rakesh.jpg',
        text: 'Full upper rehabilitation over four months. What stayed with me is the written plan on one page at the start — and the final cost matching it exactly. No surprises anywhere.',
    },
    {
        name: 'Meera Iyer',
        when: 'a month ago',
        avatar: '/assets/av-meera.jpg',
        text: 'My daughter is six and actually asks when she can go back. First visit was a mirror, a ride in the chair and a sticker. No treatment until she was ready.',
    },
    {
        name: 'Anand Sharma',
        when: '2 months ago',
        avatar: '/assets/av-anand.jpg',
        text: 'My jaw had been clicking and locking for a decade and two clinics told me to live with it. Dr. Rao took scans, made a splint, and by week six I was sleeping through the night.',
    },
    {
        name: 'Fatima Shaikh',
        when: '3 months ago',
        avatar: '/assets/av-fatima.jpg',
        text: 'They mocked up the veneers in resin and sent me home wearing them for a week. I changed my mind on the shape twice before anything permanent happened. That alone was worth it.',
    },
    {
        name: 'Daniel Fernandes',
        when: '4 months ago',
        avatar: '/assets/av-daniel.jpg',
        text: 'Booked as an emergency at 11am with a cracked tooth and was in the chair by 3. Calm, unhurried, and they explained every step on the screen next to me.',
    },
];

const STAR = 'M12 2l3 6.5 7 .9-5 4.9 1.2 7L12 18l-6.2 3.3L7 14.3 2 9.4l7-.9z';
</script>

<template>
    <section class="sec greviews" id="reviews" ref="root">
        <div class="wrap">
            <div class="g-head">
                <span class="eyebrow" data-rv>Google reviews</span>
                <h2 class="dis" data-rv style="--d:.06s">Rated <em>4.9 out of 5.</em></h2>
            </div>

            <div class="gsum" data-rv style="--d:.1s">
                <div class="gsum-brand">
                    <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    <span><b>Google Rating</b><span>Dr. Pushpa Patel's Dental Clinic</span></span>
                </div>
                <div class="gsum-score">
                    <span class="n">4.9</span>
                    <span>
                        <span class="gstars" aria-hidden="true">
                            <svg v-for="i in 5" :key="i" viewBox="0 0 24 24"><path :d="STAR"/></svg>
                        </span>
                        <small>Based on 860 reviews</small>
                    </span>
                </div>
                <a class="btn btn-brand" href="#" target="_blank" rel="noopener">Write a review
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="gcards">
                <article v-for="review in reviews" :key="review.name" class="gcard" data-rv>
                    <div class="gcard-top">
                        <img :src="review.avatar" alt="">
                        <span><b>{{ review.name }}</b><span>{{ review.when }}</span></span>
                        <svg class="gmark" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    </div>
                    <span class="gstars" aria-hidden="true">
                        <svg v-for="i in 5" :key="i" viewBox="0 0 24 24"><path :d="STAR"/></svg>
                    </span>
                    <p>{{ review.text }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
