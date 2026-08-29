<script setup>
import Sidebar from './Sidebar.vue';
import Topbar from './Topbar.vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});
</script>

<template>
    <div class="admin-shell">
        <Sidebar />

        <div class="admin-main">
            <Topbar :title="title" />
            <main class="admin-content">
                <slot />
            </main>
        </div>
    </div>
</template>
