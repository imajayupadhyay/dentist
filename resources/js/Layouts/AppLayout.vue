<script setup>
import Header from '@/Components/Global/Header.vue';
import Footer from '@/Components/Global/Footer.vue';
</script>

<template>
    <Header />

    <main id="top">
        <slot />
    </main>

    <Footer />
</template>
