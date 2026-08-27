<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/drpushpa-secure-login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Doctor Pushpa - Secure Login">
        <meta name="robots" content="noindex,nofollow">
    </Head>

    <main class="admin-auth-page">
        <section class="admin-login-card" aria-labelledby="admin-login-title">
            <div class="admin-login-brand">
                <img src="/assets/logo.png" alt="">
                <span>
                    <b>Pushpa Patel</b>
                    <span>Dental Clinic</span>
                </span>
            </div>

            <div class="admin-login-copy">
                <span class="admin-kicker">Admin panel</span>
                <h1 id="admin-login-title">Doctor Pushpa - Secure Login</h1>
                <p>Sign in with your administrator account to continue to the dashboard.</p>
            </div>

            <form class="admin-login-form" @submit.prevent="submit">
                <label>
                    <span>Email address</span>
                    <input
                        v-model="form.email"
                        type="email"
                        autocomplete="username"
                        autofocus
                        required
                        placeholder="admin@gmail.com"
                    >
                    <small v-if="form.errors.email">{{ form.errors.email }}</small>
                </label>

                <label>
                    <span>Password</span>
                    <input
                        v-model="form.password"
                        type="password"
                        autocomplete="current-password"
                        required
                        placeholder="Enter password"
                    >
                    <small v-if="form.errors.password">{{ form.errors.password }}</small>
                </label>

                <label class="admin-check">
                    <input v-model="form.remember" type="checkbox">
                    <span>Keep me signed in</span>
                </label>

                <button class="admin-login-btn" type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Signing in...' : 'Sign in securely' }}
                    <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>
        </section>
    </main>
</template>
