<script setup>
import { Link } from '@inertiajs/vue3';

const items = [
    {
        label: 'Dashboard',
        href: '/admin/dashboard',
        icon: 'M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z',
        active: true,
    },
];
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
                :class="{ active: item.active }"
            >
                <svg viewBox="0 0 24 24"><path :d="item.icon"/></svg>
                <span>{{ item.label }}</span>
            </Link>
        </nav>
    </aside>
</template>
