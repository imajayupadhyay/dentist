<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import RichText from '@/Components/Global/RichText.vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({}),
    },
});

const root = ref(null);

useScrollReveal(root);

const fallbackContent = {
    eyebrow: 'Get in touch',
    heading: 'Book your visit, ',
    heading_accent: 'in a minute.',
    map_title: "Map showing Dr. Pushpa Patel's Dental Clinic, Linking Road, Bandra West, Mumbai",
    map_src: 'https://maps.google.com/maps?q=Linking%20Road%2C%20Bandra%20West%2C%20Mumbai&z=15&output=embed',
};

const fallbackFormContent = {
    heading: 'Request an appointment',
    intro: 'Send this and the front desk will call you back the same working day. Nothing is confirmed until you have spoken to a person.',
    treatment_options: [
        { label: 'General check-up' },
        { label: 'Pain or emergency' },
        { label: 'Dental implants' },
        { label: 'Invisible aligners' },
        { label: 'Smile design' },
        { label: 'Jaw joint (TMD)' },
        { label: "Kids' dentistry" },
    ],
    time_options: [
        { label: 'Morning · 9:30 – 13:00' },
        { label: 'Afternoon · 13:00 – 17:00' },
        { label: 'Evening · 17:00 – 19:30' },
    ],
    submit_label: 'Request a call back',
    privacy_note: 'We reply the same working day. Your details are never shared.',
    success_title: "Thank you — that's with the front desk.",
    success_body: 'Someone will call you before the end of the day to confirm a time.',
};

const pageContent = computed(() => ({
    ...fallbackContent,
    ...(props.content || {}),
    form: {
        ...fallbackFormContent,
        ...((props.content || {}).form || {}),
    },
}));

const formContent = computed(() => pageContent.value.form);

const form = useForm({
    name: '',
    phone: '',
    email: '',
    treatment: '',
    preferred_date: '',
    preferred_time: '',
    message: '',
});

const sent = ref(false);

const nameInput = ref(null);
const phoneInput = ref(null);

/** Don't offer dates in the past. */
const today = computed(() => {
    const date = new Date();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

    return date.toISOString().slice(0, 10);
});

const treatmentOptions = computed(() => normalizeOptions(
    formContent.value.treatment_options,
    fallbackFormContent.treatment_options,
));

const timeOptions = computed(() => normalizeOptions(
    formContent.value.time_options,
    fallbackFormContent.time_options,
));

function submit() {
    sent.value = false;
    form.clearErrors();

    if (! form.name.trim()) {
        form.setError('name', 'Please tell us your name.');
        nameInput.value?.focus();

        return;
    }

    if (! form.phone.trim()) {
        form.setError('phone', 'A number we can reach you on.');
        phoneInput.value?.focus();

        return;
    }

    form.post('/contact-submissions', {
        preserveScroll: true,
        onSuccess: () => {
            sent.value = true;
            form.reset();
        },
        onError: () => {
            sent.value = false;

            if (form.errors.name) {
                nameInput.value?.focus();
            } else if (form.errors.phone) {
                phoneInput.value?.focus();
            }
        },
    });
}

function normalizeOptions(options, fallback) {
    const rows = Array.isArray(options) ? options : fallback;

    return rows
        .map((item) => typeof item === 'string' ? item : item?.label)
        .map((label) => String(label || '').trim())
        .filter(Boolean);
}
</script>

<template>
    <section class="sec contact" id="contact" ref="root">
        <div class="wrap">
            <div class="c-head">
                <span class="eyebrow" data-rv>{{ pageContent.eyebrow }}</span>
                <h2 class="dis" data-rv style="--d:.06s">
                    {{ pageContent.heading }}<em v-if="pageContent.heading_accent">{{ pageContent.heading_accent }}</em>
                </h2>
            </div>

            <div class="c-grid">

                <div class="map-card" data-rv>
                    <iframe
                        :title="pageContent.map_title"
                        :src="pageContent.map_src"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>

                <div class="form-card" id="book" data-rv style="--d:.08s">
                    <h3>{{ formContent.heading }}</h3>
                    <RichText v-if="formContent.intro" class="contact-form-intro" :html="formContent.intro" />

                    <form class="cform" novalidate @submit.prevent="submit">
                        <div class="f2">
                            <div class="fld" :class="{ err: form.errors.name }">
                                <label for="c-name">Full name <i>*</i></label>
                                <div class="box">
                                    <input
                                        id="c-name"
                                        ref="nameInput"
                                        v-model="form.name"
                                        type="text"
                                        autocomplete="name"
                                        placeholder="Your name"
                                        @input="form.clearErrors('name')"
                                    >
                                </div>
                                <span class="msg">{{ form.errors.name || 'Please tell us your name.' }}</span>
                            </div>
                            <div class="fld" :class="{ err: form.errors.phone }">
                                <label for="c-phone">Phone number <i>*</i></label>
                                <div class="box">
                                    <input
                                        id="c-phone"
                                        ref="phoneInput"
                                        v-model="form.phone"
                                        type="tel"
                                        autocomplete="tel"
                                        placeholder="+91 98200 00000"
                                        @input="form.clearErrors('phone')"
                                    >
                                </div>
                                <span class="msg">{{ form.errors.phone || 'A number we can reach you on.' }}</span>
                            </div>
                        </div>

                        <div class="f2">
                            <div class="fld" :class="{ err: form.errors.email }">
                                <label for="c-email">Email <span style="opacity:.6">(optional)</span></label>
                                <div class="box">
                                    <input id="c-email" v-model="form.email" type="email" autocomplete="email" placeholder="you@example.com" @input="form.clearErrors('email')">
                                </div>
                                <span v-if="form.errors.email" class="msg">{{ form.errors.email }}</span>
                            </div>
                            <div class="fld" :class="{ err: form.errors.treatment }">
                                <label for="c-treat">What is it about?</label>
                                <div class="box">
                                    <select id="c-treat" v-model="form.treatment" @change="form.clearErrors('treatment')">
                                        <option value="">Choose a treatment</option>
                                        <option v-for="item in treatmentOptions" :key="item">{{ item }}</option>
                                    </select>
                                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span v-if="form.errors.treatment" class="msg">{{ form.errors.treatment }}</span>
                            </div>
                        </div>

                        <div class="f2">
                            <div class="fld" :class="{ err: form.errors.preferred_date }">
                                <label for="c-date">Preferred date</label>
                                <div class="box">
                                    <input id="c-date" v-model="form.preferred_date" type="date" :min="today" @input="form.clearErrors('preferred_date')">
                                </div>
                                <span v-if="form.errors.preferred_date" class="msg">{{ form.errors.preferred_date }}</span>
                            </div>
                            <div class="fld" :class="{ err: form.errors.preferred_time }">
                                <label for="c-time">Preferred time</label>
                                <div class="box">
                                    <select id="c-time" v-model="form.preferred_time" @change="form.clearErrors('preferred_time')">
                                        <option value="">Any time</option>
                                        <option v-for="item in timeOptions" :key="item">{{ item }}</option>
                                    </select>
                                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span v-if="form.errors.preferred_time" class="msg">{{ form.errors.preferred_time }}</span>
                            </div>
                        </div>

                        <div class="fld" :class="{ err: form.errors.message }">
                            <label for="c-msg">Anything you'd like us to know</label>
                            <div class="box">
                                <textarea id="c-msg" v-model="form.message" rows="3" placeholder="Symptoms, past treatment, anything you're anxious about&hellip;" @input="form.clearErrors('message')"></textarea>
                            </div>
                            <span v-if="form.errors.message" class="msg">{{ form.errors.message }}</span>
                        </div>

                        <div class="cform-foot">
                            <button class="btn btn-brand" type="submit" :disabled="form.processing">{{ form.processing ? 'Sending...' : formContent.submit_label }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </button>
                            <small v-if="formContent.privacy_note">{{ formContent.privacy_note }}</small>
                        </div>

                        <div class="c-ok" :class="{ show: sent }" role="status">
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>
                                <b>{{ formContent.success_title }}</b>
                                <RichText class="contact-success-copy" :html="formContent.success_body" />
                            </span>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</template>
