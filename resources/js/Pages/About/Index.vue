<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Masthead from './Components/Masthead.vue';
import Figures from './Components/Figures.vue';
import FoundersNote from './Components/FoundersNote.vue';
import Values from './Components/Values.vue';
import Team from './Components/Team.vue';
import Cta from './Components/Cta.vue';
</script>

<template>
    <Head title="About us">
        <meta name="description" content="About Dr. Pushpa Patel's Dental Clinic - sixteen years of unhurried, plan-first dentistry on Linking Road, Bandra West, Mumbai.">
    </Head>

    <AppLayout>
        <Masthead />
        <Figures />
        <FoundersNote />
        <Values />
        <Team />
        <Cta />
    </AppLayout>
</template>
