<script setup>
import { Head } from '@inertiajs/vue3';
import AdminShell from '../Components/AdminShell.vue';
</script>

<template>
    <Head title="Admin Dashboard">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Dashboard">
        <section class="admin-dashboard">
            <div class="admin-dashboard-hero">
                <span class="admin-kicker">Overview</span>
                <h2>Welcome to the Admin Dashboard</h2>
                <p>This foundation is ready for future clinic modules. Sidebar and topbar are reusable admin components.</p>
            </div>

            <div class="admin-metrics" aria-label="Dashboard summary">
                <article>
                    <span>Appointments</span>
                    <b>0</b>
                    <small>Backend module pending</small>
                </article>
                <article>
                    <span>Patients</span>
                    <b>0</b>
                    <small>Backend module pending</small>
                </article>
                <article>
                    <span>Messages</span>
                    <b>0</b>
                    <small>Backend module pending</small>
                </article>
            </div>
        </section>
    </AdminShell>
</template>
