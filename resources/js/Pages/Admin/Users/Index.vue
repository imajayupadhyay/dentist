<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AdminShell from '../Components/AdminShell.vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            admins: 0,
        }),
    },
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const rows = ref([]);

const createForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

watch(
    () => props.users,
    (items) => {
        rows.value = items.map((user) => ({
            ...user,
            form: useForm({
                name: user.name,
                email: user.email,
                password: '',
                password_confirmation: '',
            }),
        }));
    },
    { immediate: true },
);

function createAdmin() {
    createForm.post('/admin/users', {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

function updateAdmin(row) {
    row.form.put(`/admin/users/${row.id}`, {
        preserveScroll: true,
        onSuccess: () => row.form.reset('password', 'password_confirmation'),
    });
}

function deleteAdmin(row) {
    if (! row.can_delete) {
        return;
    }

    if (! window.confirm(`Delete admin account for ${row.name}?`)) {
        return;
    }

    router.delete(`/admin/users/${row.id}`, {
        preserveScroll: true,
    });
}

function initials(name) {
    return String(name || 'A')
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase();
}
</script>

<template>
    <Head title="Admin Users">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <AdminShell title="Users">
        <section class="admin-treatment-page admin-user-page">
            <div class="admin-treatment-toolbar">
                <div>
                    <span class="admin-kicker">Access module</span>
                    <h2>Users</h2>
                    <p>Create and maintain admin accounts that can sign in to the admin panel.</p>
                </div>

                <div class="admin-toolbar-actions">
                    <Link class="admin-action-secondary" href="/admin/dashboard">Dashboard</Link>
                </div>
            </div>

            <p v-if="flash.success" class="admin-flash success">{{ flash.success }}</p>
            <p v-if="flash.error" class="admin-flash error">{{ flash.error }}</p>

            <div class="admin-user-stats">
                <article>
                    <span>Admin accounts</span>
                    <b>{{ stats.admins ?? users.length }}</b>
                </article>
            </div>

            <form class="admin-treatment-panel admin-user-create" @submit.prevent="createAdmin">
                <div class="admin-panel-head">
                    <span>01</span>
                    <div>
                        <h3>Add admin</h3>
                        <p>New users created here can access every protected admin page.</p>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <label class="admin-field">
                        <span>Name</span>
                        <input v-model="createForm.name" type="text" autocomplete="name">
                        <small v-if="createForm.errors.name">{{ createForm.errors.name }}</small>
                    </label>

                    <label class="admin-field">
                        <span>Email</span>
                        <input v-model="createForm.email" type="email" autocomplete="email">
                        <small v-if="createForm.errors.email">{{ createForm.errors.email }}</small>
                    </label>

                    <label class="admin-field">
                        <span>Password</span>
                        <input v-model="createForm.password" type="password" autocomplete="new-password">
                        <small v-if="createForm.errors.password">{{ createForm.errors.password }}</small>
                    </label>

                    <label class="admin-field">
                        <span>Confirm password</span>
                        <input v-model="createForm.password_confirmation" type="password" autocomplete="new-password">
                        <small v-if="createForm.errors.password_confirmation">{{ createForm.errors.password_confirmation }}</small>
                    </label>
                </div>

                <div class="admin-user-form-actions">
                    <button class="admin-action-primary" type="submit" :disabled="createForm.processing">
                        {{ createForm.processing ? 'Creating...' : 'Create admin' }}
                        <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    </button>
                </div>
            </form>

            <section class="admin-treatment-panel admin-user-list-panel">
                <div class="admin-panel-head">
                    <span>02</span>
                    <div>
                        <h3>Existing admins</h3>
                        <p>Leave password fields blank when you only want to update name or email.</p>
                    </div>
                </div>

                <div class="admin-user-list">
                    <article v-for="row in rows" :key="row.id" class="admin-user-item">
                        <div class="admin-user-avatar">{{ initials(row.form.name) }}</div>

                        <form class="admin-user-edit" @submit.prevent="updateAdmin(row)">
                            <div class="admin-user-title">
                                <div>
                                    <span class="admin-status active">Admin</span>
                                    <span v-if="row.is_current" class="admin-status current">Current user</span>
                                    <h4>{{ row.form.name || 'Admin user' }}</h4>
                                    <p>Created {{ row.created_at || 'recently' }}</p>
                                </div>

                                <div class="admin-user-actions">
                                    <button type="submit" :disabled="row.form.processing">
                                        {{ row.form.processing ? 'Saving...' : 'Save' }}
                                    </button>
                                    <button
                                        type="button"
                                        class="danger"
                                        :disabled="! row.can_delete"
                                        :title="row.can_delete ? 'Delete admin' : 'This admin cannot be deleted'"
                                        @click="deleteAdmin(row)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <div class="admin-form-grid">
                                <label class="admin-field">
                                    <span>Name</span>
                                    <input v-model="row.form.name" type="text" autocomplete="name">
                                    <small v-if="row.form.errors.name">{{ row.form.errors.name }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Email</span>
                                    <input v-model="row.form.email" type="email" autocomplete="email">
                                    <small v-if="row.form.errors.email">{{ row.form.errors.email }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>New password</span>
                                    <input v-model="row.form.password" type="password" autocomplete="new-password">
                                    <small v-if="row.form.errors.password">{{ row.form.errors.password }}</small>
                                </label>

                                <label class="admin-field">
                                    <span>Confirm new password</span>
                                    <input v-model="row.form.password_confirmation" type="password" autocomplete="new-password">
                                    <small v-if="row.form.errors.password_confirmation">{{ row.form.errors.password_confirmation }}</small>
                                </label>
                            </div>
                        </form>
                    </article>

                    <div v-if="rows.length === 0" class="admin-empty">
                        <h3>No admin users found</h3>
                        <p>Create the first admin account from the form above.</p>
                    </div>
                </div>
            </section>
        </section>
    </AdminShell>
</template>
