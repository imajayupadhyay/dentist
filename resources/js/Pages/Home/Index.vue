<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Hero from './Components/Hero.vue';
import About from './Components/About.vue';
import Treatments from './Components/Treatments.vue';
import Stories from './Components/Stories.vue';
import Reviews from './Components/Reviews.vue';
import Contact from './Components/Contact.vue';

defineProps({
    treatments: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <AppLayout>
        <Hero />
        <About />
        <Treatments :treatments="treatments" />
        <Stories />
        <Reviews />
        <Contact />
    </AppLayout>
</template>
