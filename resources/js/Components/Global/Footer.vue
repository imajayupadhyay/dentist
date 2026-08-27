<script setup>
import { computed } from 'vue';

const year = computed(() => new Date().getFullYear());

/** wa.me needs the number in international format with no +, spaces or dashes. */
const whatsapp = 'https://wa.me/919820000000?text=' +
    encodeURIComponent("Hello, I'd like to book a dental appointment.");

const treatments = [
    { label: 'Painless dentistry', href: '/#treatments' },
    { label: 'Dental implants', href: '/#treatments' },
    { label: 'Invisible aligners', href: '/#treatments' },
    { label: 'Smile design', href: '/#treatments' },
    { label: 'Jaw joint & TMD', href: '/#treatments' },
    { label: "Kids' dentistry", href: '/#treatments' },
];

const clinic = [
    { label: 'About the clinic', href: '/about-us' },
    { label: 'Our dentists', href: '/about-us#team' },
    { label: 'Patient stories', href: '/#stories' },
    { label: 'Google reviews', href: '/#reviews' },
    { label: 'Find us', href: '/#contact' },
    { label: 'Book an appointment', href: '/#book' },
];
</script>

<template>
    <footer class="ft">
        <div class="wrap">

            <div class="ft-cta">
                <span class="ic">
                    <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                </span>
                <span>
                    <b>Dental emergency?</b>
                    <span>A slot is held every weekday afternoon. Call before noon and you will be seen today.</span>
                </span>
                <div class="ft-cta-actions">
                    <a class="btn" href="tel:+919820000000">Call +91 98200 00000
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a
                        class="btn btn-wa"
                        :href="whatsapp"
                        target="_blank"
                        rel="noopener"
                        aria-label="Message the clinic on WhatsApp"
                    >
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.019-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 21.785h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.002-5.45 4.437-9.884 9.888-9.884a9.82 9.82 0 016.988 2.896 9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.885-9.885 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.359.101 11.945c0 2.096.549 4.142 1.595 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.585 0 11.946-5.359 11.949-11.945a11.87 11.87 0 00-3.421-8.404"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <div class="ft-top">
                <div>
                    <div class="ft-brand">
                        <img src="/assets/logo.png" alt="">
                        <span><b>Pushpa Patel</b><span>Dental Clinic</span></span>
                    </div>
                    <p class="ft-blurb">Modern, unhurried dentistry in Bandra West since 2009. Implants, aligners, smile design, jaw joint care and full-mouth rehabilitation, all under one roof.</p>
                    <div class="socs">
                        <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.8.07 1.2.05 1.8.25 2.2.42.6.22 1 .48 1.4.9.4.4.68.8.9 1.4.17.4.37 1 .42 2.2.06 1.2.07 1.6.07 4.8s0 3.6-.07 4.8c-.05 1.2-.25 1.8-.42 2.2-.22.6-.5 1-.9 1.4-.4.4-.8.68-1.4.9-.4.17-1 .37-2.2.42-1.2.06-1.6.07-4.8.07s-3.6 0-4.8-.07c-1.2-.05-1.8-.25-2.2-.42-.6-.22-1-.5-1.4-.9-.4-.4-.68-.8-.9-1.4-.17-.4-.37-1-.42-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.8c.05-1.2.25-1.8.42-2.2.22-.6.5-1 .9-1.4.4-.42.8-.68 1.4-.9.4-.17 1-.37 2.2-.42C8.4 2.2 8.8 2.2 12 2.2zm0 3.05A6.75 6.75 0 1018.75 12 6.75 6.75 0 0012 5.25zm0 11.13A4.38 4.38 0 1116.38 12 4.38 4.38 0 0112 16.38zm8.6-11.4a1.58 1.58 0 11-1.58-1.57 1.58 1.58 0 011.57 1.57z"/></svg></a>
                        <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5h1.65V4.6A22 22 0 0014.3 4.4c-2.4 0-4 1.45-4 4.15v2.35H7.6V14h2.7v8z"/></svg></a>
                        <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M22 12s0-3.2-.4-4.7a2.5 2.5 0 00-1.75-1.75C18.3 5.1 12 5.1 12 5.1s-6.3 0-7.85.45A2.5 2.5 0 002.4 7.3C2 8.8 2 12 2 12s0 3.2.4 4.7a2.5 2.5 0 001.75 1.75C5.7 18.9 12 18.9 12 18.9s6.3 0 7.85-.45A2.5 2.5 0 0021.6 16.7C22 15.2 22 12 22 12zM9.95 15.05v-6.1L15.2 12z"/></svg></a>
                        <a href="#" aria-label="Find us on Google Maps"><svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1114.5 9 2.5 2.5 0 0112 11.5z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h4>Treatments</h4>
                    <ul>
                        <li v-for="item in treatments" :key="item.label"><a :href="item.href">{{ item.label }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Clinic</h4>
                    <ul>
                        <li v-for="item in clinic" :key="item.label"><a :href="item.href">{{ item.label }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Visit us</h4>
                    <div class="ft-contact">
                        <div>
                            <svg viewBox="0 0 24 24"><path d="M12 21s-7-5.8-7-11a7 7 0 1114 0c0 5.2-7 11-7 11z"/><circle cx="12" cy="10" r="2.4"/></svg>
                            <span>2nd Floor, Turner House,<br>Linking Road, Bandra West,<br>Mumbai 400050</span>
                        </div>
                        <div>
                            <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                            <span><a href="tel:+912226000000">+91 22 2600 0000</a></span>
                        </div>
                        <div>
                            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2.4"/><path d="M3.6 6.6l8.4 6 8.4-6"/></svg>
                            <span><a href="mailto:care@pushpapateldental.in">care@pushpapateldental.in</a></span>
                        </div>
                        <div>
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            <span>Mon&ndash;Fri 9:30&ndash;19:30<br>Sat 9:30&ndash;15:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ft-bot">
                <span>&copy; <span>{{ year }}</span> Dr. Pushpa Patel's Dental Clinic. All rights reserved.</span>
                <nav aria-label="Legal">
                    <a href="#">Privacy policy</a>
                    <a href="#">Terms &amp; conditions</a>
                    <a href="#">Sitemap</a>
                </nav>
                <a class="ft-up" href="#top">
                    Back to top
                    <svg viewBox="0 0 24 24"><path d="M12 19V5M6 11l6-6 6 6"/></svg>
                </a>
            </div>

            <p class="ft-note"><b>Demonstration build.</b> All copy, statistics, patient reviews, contact details and opening hours are placeholder content and must be replaced before launch. Photography is stock direction, not the clinic. The video testimonials are generated sample clips. The appointment form is front-end only and sends nothing, and the map points at a placeholder location.</p>
        </div>
    </footer>
</template>
