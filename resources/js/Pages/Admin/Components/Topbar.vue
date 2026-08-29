<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

function logout() {
    router.post('/admin/logout');
}
</script>

<template>
    <header class="admin-topbar">
        <div>
            <span class="admin-kicker">Secure area</span>
            <h1>{{ title }}</h1>
        </div>

        <div class="admin-topbar-actions">
            <span class="admin-user">
                <b>{{ user?.name ?? 'Admin' }}</b>
                <small>{{ user?.email }}</small>
            </span>
            <button type="button" class="admin-logout" @click="logout">
                Logout
                <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M21 4v16"/></svg>
            </button>
        </div>
    </header>
</template>
