<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';

const props = defineProps({
    submissions: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            new: 0,
            contacted: 0,
            closed: 0,
            today: 0,
        }),
    },
    statuses: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const rows = ref([]);

const statCards = computed(() => [
    { label: 'Total', value: props.stats.total ?? 0 },
    { label: 'New', value: props.stats.new ?? 0 },
    { label: 'Today', value: props.stats.today ?? 0 },
    { label: 'Contacted', value: props.stats.contacted ?? 0 },
    { label: 'Closed', value: props.stats.closed ?? 0 },
]);

const statusOptions = computed(() => props.statuses.length
    ? props.statuses
    : [
        { value: 'new', label: 'New' },
        { value: 'contacted', label: 'Contacted' },
        { value: 'closed', label: 'Closed' },
    ]);

watch(
    () => props.submissions,
    (items) => {
        rows.value = items.map((item) => ({
            ...item,
            admin_notes: item.admin_notes || '',
            processing: false,
        }));
    },
    { immediate: true },
);

function save(row) {
    row.processing = true;

    router.patch(`/admin/contacts/${row.id}`, {
        status: row.status,
        admin_notes: row.admin_notes,
    }, {
        preserveScroll: true,
        onFinish: () => {
            row.processing = false;
        },
    });
}

function phoneHref(phone) {
    return `tel:${String(phone || '').replace(/[^\d+]/g, '')}`;
}

function mailHref(email) {
    return `mailto:${email}`;
}
</script>

<template>
    <Head title="Admin Contacts">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Contacts">
        <section class="admin-treatment-page admin-contact-page">
            <div class="admin-treatment-toolbar">
                <div>
                    <span class="admin-kicker">Inbox module</span>
                    <h2>Contacts</h2>
                    <p>Review homepage appointment requests and keep their follow-up status updated.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <a class="admin-action-secondary" href="/#book" target="_blank" rel="noopener">View form</a>
                    <Link class="admin-action-secondary" href="/admin/dashboard">Dashboard</Link>
                </div>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div class="admin-contact-stats">
                <article v-for="item in statCards" :key="item.label">
                    <span>{{ item.label }}</span>
                    <b>{{ item.value }}</b>
                </article>
            </div>

            <div class="admin-contact-list">
                <article v-for="row in rows" :key="row.id" class="admin-contact-item">
                    <div class="admin-contact-head">
                        <div>
                            <span :class="['admin-status', row.status]">{{ row.status_label }}</span>
                            <h3>{{ row.name }}</h3>
                            <p>{{ row.created_at }} · {{ row.source_page || 'home' }}</p>
                        </div>

                        <div class="admin-contact-actions">
                            <label>
                                <span>Status</span>
                                <select v-model="row.status">
                                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
                                </select>
                            </label>
                            <button type="button" :disabled="row.processing" @click="save(row)">
                                {{ row.processing ? 'Saving...' : 'Save' }}
                            </button>
                        </div>
                    </div>

                    <dl class="admin-contact-meta">
                        <div>
                            <dt>Phone</dt>
                            <dd><a :href="phoneHref(row.phone)">{{ row.phone }}</a></dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>
                                <a v-if="row.email" :href="mailHref(row.email)">{{ row.email }}</a>
                                <span v-else>Not provided</span>
                            </dd>
                        </div>
                        <div>
                            <dt>Treatment</dt>
                            <dd>{{ row.treatment || 'Not selected' }}</dd>
                        </div>
                        <div>
                            <dt>Preferred time</dt>
                            <dd>{{ [row.preferred_date, row.preferred_time].filter(Boolean).join(' · ') || 'Any time' }}</dd>
                        </div>
                    </dl>

                    <div v-if="row.message" class="admin-contact-message">
                        <span>Message</span>
                        <p>{{ row.message }}</p>
                    </div>

                    <label class="admin-contact-notes">
                        <span>Admin notes</span>
                        <textarea v-model="row.admin_notes" rows="3" placeholder="Add follow-up notes for the team"></textarea>
                    </label>
                </article>

                <div v-if="rows.length === 0" class="admin-empty">
                    <h3>No contact requests yet</h3>
                    <p>Homepage form submissions will appear here as soon as patients send them.</p>
                </div>
            </div>
        </section>
    </AdminShell>
</template>
