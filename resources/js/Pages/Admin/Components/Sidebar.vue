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
