<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const currentPath = computed(() => page.url.split('?')[0]);

const items = [
    {
        label: 'Dashboard',
        href: '/admin/dashboard',
        icon: 'M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z',
        match: '/admin/dashboard',
    },
    {
        label: 'Header',
        href: '/admin/header',
        icon: 'M4 5h16v4H4V5zm0 6h10v8H4v-8zm12 0h4v8h-4v-8z',
        match: '/admin/header',
    },
    {
        label: 'Footer',
        href: '/admin/footer',
        icon: 'M4 5h16v5H4V5zm0 7h4v7H4v-7zm6 0h4v7h-4v-7zm6 0h4v7h-4v-7z',
        match: '/admin/footer',
    },
    {
        label: 'Home',
        href: '/admin/home',
        icon: 'M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1h-5v-6h-4v6H5a1 1 0 01-1-1v-9.5z',
        match: '/admin/home',
    },
    {
        label: 'About',
        href: '/admin/about',
        icon: 'M12 4a4 4 0 110 8 4 4 0 010-8zM4 21a8 8 0 0116 0H4z',
        match: '/admin/about',
    },
    {
        label: 'Contacts',
        href: '/admin/contacts',
        icon: 'M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11zm2.4.5l5.6 4.2L17.6 7H6.4zm11.1 10.5V9.2l-5.5 4.1-5.5-4.1v8.3h11z',
        match: '/admin/contacts',
    },
    {
        label: 'Users',
        href: '/admin/users',
        icon: 'M9 11a4 4 0 110-8 4 4 0 010 8zm7-1a3 3 0 100-6 3 3 0 000 6zM3.5 21a5.5 5.5 0 0111 0h-11zm10.5 0a6.5 6.5 0 00-2.2-4.8A4.8 4.8 0 0116 14a5 5 0 015 5v2h-7z',
        match: '/admin/users',
    },
    {
        label: 'Treatments',
        href: '/admin/treatments',
        icon: 'M12 3c-3 0-4.6 1.9-4.6 4.6 0 2.2.8 3.4.8 6.2 0 1.9-.6 3.4-.6 5.1 0 1.4.7 2.1 1.7 2.1 1.9 0 1.6-4.4 2.7-4.4s.8 4.4 2.7 4.4c1 0 1.7-.7 1.7-2.1 0-1.7-.6-3.2-.6-5.1 0-2.8.8-4 .8-6.2C16.6 4.9 15 3 12 3z',
        match: '/admin/treatments',
    },
];

function isActive(item) {
    return currentPath.value === item.match || currentPath.value.startsWith(`${item.match}/`);
}
</script>

<template>
    <aside class="admin-sidebar" aria-label="Admin navigation">
        <Link class="admin-sidebar-brand" href="/admin/dashboard">
            <img src="/assets/logo.png" alt="">
            <span>
                <b>Doctor Pushpa</b>
                <small>Admin Panel</small>
            </span>
        </Link>

        <nav class="admin-sidebar-nav">
            <Link
                v-for="item in items"
                :key="item.href"
                :href="item.href"
                :class="{ active: isActive(item) }"
            >
                <svg viewBox="0 0 24 24"><path :d="item.icon"/></svg>
                <span>{{ item.label }}</span>
            </Link>
        </nav>
    </aside>
</template>
