<script setup>
import Sidebar from './Sidebar.vue';
import Topbar from './Topbar.vue';
</script>

<template>
    <div class="admin-shell">
        <Sidebar />

        <div class="admin-main">
            <Topbar />
            <main class="admin-content">
                <slot />
            </main>
        </div>
    </div>
</template>
