<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const slim = ref(false);
const open = ref(false);
const page = usePage();

const links = [
    { label: 'About Us', href: '/about-us', currentPath: '/about-us' },
    { label: 'Treatments', href: '/#treatments' },
    { label: 'Doctors', href: '/about-us#team' },
    { label: 'Reviews', href: '/#reviews' },
    { label: 'Contact', href: '/#contact' },
];

const currentPath = computed(() => {
    const [withoutHash] = page.url.split('#');
    return withoutHash.split('?')[0] || '/';
});

const appointmentHref = computed(() => currentPath.value === '/' ? '#book' : '/#book');
const appointmentComponent = computed(() => currentPath.value === '/' ? 'a' : Link);
const brandHref = computed(() => currentPath.value === '/' ? '#top' : '/');
const brandComponent = computed(() => currentPath.value === '/' ? 'a' : Link);

function normalizedHref(href) {
    if (currentPath.value === '/' && href.startsWith('/#')) {
        return href.slice(1);
    }

    if (href.startsWith(`${currentPath.value}#`)) {
        return href.slice(currentPath.value.length);
    }

    return href;
}

function linkComponent(href) {
    return normalizedHref(href).startsWith('#') ? 'a' : Link;
}

function isCurrent(link) {
    return link.currentPath === currentPath.value;
}

function onScroll() {
    slim.value = window.scrollY > 20;
}

function onKeydown(event) {
    if (event.key === 'Escape' && open.value) {
        open.value = false;
    }
}

/** The drawer is mobile-only — never leave it open behind a desktop layout. */
function onResize() {
    if (window.innerWidth > 860) {
        open.value = false;
    }
}

// Lock the page behind the drawer while it is open.
watch(open, (isOpen) => {
    document.body.classList.toggle('mnav-lock', isOpen);
});

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);
    window.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('resize', onResize);
    window.removeEventListener('keydown', onKeydown);
    document.body.classList.remove('mnav-lock');
});
</script>

<template>
    <header class="nav" :class="{ slim, open }">
        <div class="nav-in">
            <component :is="brandComponent" class="brand" :href="brandHref" aria-label="Dr. Pushpa Patel's Dental Clinic — home">
                <img src="/assets/logo.png" alt="">
                <span><b>Pushpa Patel</b><span>Dental Clinic</span></span>
            </component>

            <nav class="nav-links" aria-label="Primary">
                <component
                    :is="linkComponent(link.href)"
                    v-for="link in links"
                    :key="link.href"
                    :href="normalizedHref(link.href)"
                    :aria-current="isCurrent(link) ? 'page' : null"
                >{{ link.label }}</component>
            </nav>

            <a class="nav-call" href="tel:+919820000000">
                <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                +91 98200 00000
            </a>

            <component :is="appointmentComponent" class="btn btn-brand" :href="appointmentHref">Book appointment
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </component>

            <button
                class="burger"
                :aria-label="open ? 'Close menu' : 'Menu'"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mnav"
                @click="open = !open"
            ><i></i></button>
        </div>
    </header>

    <!-- Full-screen drawer. Sibling of .nav on purpose — see mobile-nav.css. -->
    <div
        class="mnav"
        id="mnav"
        :class="{ open }"
        role="dialog"
        aria-modal="true"
        aria-label="Menu"
        :aria-hidden="open ? 'false' : 'true'"
    >
        <nav class="mnav-links" aria-label="Mobile">
            <component
                :is="linkComponent(link.href)"
                v-for="(link, index) in links"
                :key="link.href"
                :href="normalizedHref(link.href)"
                :aria-current="isCurrent(link) ? 'page' : null"
                :style="{ '--i': index }"
                :tabindex="open ? null : -1"
                @click="open = false"
            >
                {{ link.label }}
                <svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
            </component>
        </nav>

        <div class="mnav-foot">
            <a class="mnav-call" href="tel:+919820000000" :tabindex="open ? null : -1">
                <svg viewBox="0 0 24 24"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                +91 98200 00000
            </a>

            <component :is="appointmentComponent" class="btn btn-brand" :href="appointmentHref" :tabindex="open ? null : -1" @click="open = false">
                Book appointment
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </component>

            <p class="mnav-meta">2nd Floor, Turner House, Linking Road, Bandra West, Mumbai 400050<br>Mon&ndash;Fri 9:30&ndash;19:30 &middot; Sat 9:30&ndash;15:00</p>
        </div>
    </div>
</template>
