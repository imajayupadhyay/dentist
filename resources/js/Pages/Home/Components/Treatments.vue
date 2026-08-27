<script setup>
import { ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

/**
 * Each band owns its colour scheme through four custom properties, exactly as in
 * the source design — recolouring a treatment is a one-line change here.
 */
const treatments = [
    {
        num: '01',
        title: 'Painless Dentistry',
        sub: 'Comfort first, always.',
        copy: 'Numbing before the needle, computer-controlled delivery and a hand signal that stops everything the moment you raise it.',
        image: '/assets/elderly-care.jpg',
        alt: 'A patient relaxed in the chair during treatment',
        theme: '--bg:#cf0039;--fg:#ffffff;--sub:rgba(255,255,255,.95);--accent:#ffe0e8',
        icon: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    },
    {
        num: '02',
        title: 'Dental Implants',
        sub: 'Permanent teeth, precisely placed.',
        copy: 'Guided placement from a CBCT-planned stent, with provisional teeth the same day wherever the bone allows it.',
        image: '/assets/planning.jpg',
        alt: 'Digital implant planning on screen',
        theme: '--bg:#16162a;--fg:#ffffff;--sub:rgba(255,255,255,.84);--accent:#ffc24d',
        icon: '<path d="M12 3c-3 0-4.6 1.9-4.6 4.6 0 2.2.8 3.4.8 6.2 0 1.9-.6 3.4-.6 5.1 0 1.4.7 2.1 1.7 2.1 1.9 0 1.6-4.4 2.7-4.4s.8 4.4 2.7 4.4c1 0 1.7-.7 1.7-2.1 0-1.7-.6-3.2-.6-5.1 0-2.8.8-4 .8-6.2C16.6 4.9 15 3 12 3z"/>',
    },
    {
        num: '03',
        title: 'Invisible Aligners',
        sub: 'Straight teeth, quietly.',
        copy: 'Scan-to-plan in a single visit, with a 3D simulation of your finished bite before you commit to the first tray.',
        image: '/assets/smile-closeup.jpg',
        alt: 'A close view of an aligned smile',
        theme: '--bg:#ffc24d;--fg:#1b1b2b;--sub:rgba(27,27,43,.84);--accent:#a3130a',
        icon: '<rect x="3" y="8" width="18" height="8" rx="4"/><path d="M8 8v8M12 8v8M16 8v8"/>',
    },
    {
        num: '04',
        title: 'Smile Design',
        sub: 'Designed before it is made.',
        copy: 'Your new smile is mocked up in resin and worn home for a week — you approve the shape in your own mirror, not ours.',
        image: '/assets/whitening.jpg',
        alt: 'A shade-matching whitening treatment',
        theme: '--bg:#00c2a0;--fg:#04372c;--sub:rgba(4,55,44,.95);--accent:#053026',
        icon: '<circle cx="12" cy="12" r="3.2"/><path d="M12 3v2.4M12 18.6V21M3 12h2.4M18.6 12H21M5.6 5.6l1.7 1.7M16.7 16.7l1.7 1.7M18.4 5.6l-1.7 1.7M7.3 16.7l-1.7 1.7"/>',
    },
    {
        num: '05',
        title: 'Jaw Joint & TMD',
        sub: 'Align, relieve, smile.',
        copy: 'Clicking, locking, morning headaches and unexplained ear pain — worked up with joint imaging, muscle mapping and splint therapy.',
        image: '/assets/portrait-warm.jpg',
        alt: 'A patient after jaw joint therapy',
        theme: '--bg:#5a37e0;--fg:#ffffff;--sub:rgba(255,255,255,.93);--accent:#ffd76b',
        icon: '<path d="M12 2v20M7 6h10M6.5 11h11M8 16h8"/>',
    },
    {
        num: '06',
        title: "Kids' Dentistry",
        sub: 'First visits without fear.',
        copy: 'A mirror, a ride in the chair and a sticker. No treatment at all until the room has stopped being frightening.',
        image: '/assets/pediatric.jpg',
        alt: 'A child at a first dental visit',
        theme: '--bg:#fff0e4;--fg:#1b1b2b;--sub:rgba(27,27,43,.82);--accent:#c40036',
        icon: '<circle cx="12" cy="12" r="9"/><path d="M8.5 14.5a4.5 4.5 0 007 0M9 9.5h.01M15 9.5h.01"/>',
    },
];
</script>

<template>
    <section id="treatments" ref="root">
        <article
            v-for="(item, index) in treatments"
            :key="item.num"
            class="tband"
            :class="{ flip: index % 2 === 1 }"
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
                        <a class="tmore" href="#book">Read more
                            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </section>
</template>
