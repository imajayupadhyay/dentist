<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminShell from '../Components/AdminShell.vue';

defineProps({
    treatments: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});

function destroyTreatment(treatment) {
    if (! window.confirm(`Delete ${treatment.home_title}? This cannot be undone.`)) {
        return;
    }

    router.delete(`/admin/treatments/${treatment.slug}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Admin Treatments">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Treatments">
        <section class="admin-treatment-page">
            <div class="admin-treatment-toolbar">
                <div>
                    <span class="admin-kicker">Content module</span>
                    <h2>Treatments</h2>
                    <p>Manage homepage treatment bands and the matching treatment detail pages.</p>
                </div>

                <Link class="admin-action-primary" href="/admin/treatments/create">
                    Add treatment
                    <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                </Link>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div class="admin-treatment-list">
                <article v-for="treatment in treatments" :key="treatment.id" class="admin-treatment-item">
                    <img :src="treatment.home_image" :alt="treatment.home_title">

                    <div>
                        <div class="admin-treatment-item-top">
                            <span>#{{ String(treatment.sort_order).padStart(2, '0') }}</span>
                            <span :class="['admin-status', treatment.is_active ? 'active' : 'draft']">
                                {{ treatment.is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </div>
                        <h3>{{ treatment.home_title }}</h3>
                        <p>{{ treatment.home_subtitle }}</p>
                        <small>/treatments/{{ treatment.slug }}</small>
                    </div>

                    <div class="admin-treatment-actions">
                        <a :href="treatment.public_url" target="_blank" rel="noopener">View</a>
                        <Link :href="`/admin/treatments/${treatment.slug}/edit`">Edit</Link>
                        <button type="button" @click="destroyTreatment(treatment)">Delete</button>
                    </div>
                </article>

                <div v-if="treatments.length === 0" class="admin-empty">
                    <h3>No treatments yet</h3>
                    <p>Add the first treatment to populate the homepage section.</p>
                </div>
            </div>
        </section>
    </AdminShell>
</template>
