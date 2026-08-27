<script setup>
import { computed, reactive, ref } from 'vue';
import { useScrollReveal } from '@/Composables/useScrollReveal';

const root = ref(null);

useScrollReveal(root);

/**
 * Front-end only, exactly as in the source design — nothing is submitted.
 * See workflow.md §7 for wiring this to the backend with Inertia's useForm.
 */
const form = reactive({
    name: '',
    phone: '',
    email: '',
    treatment: '',
    date: '',
    time: '',
    message: '',
});

const errors = reactive({ name: false, phone: false });
const sent = ref(false);

const nameInput = ref(null);
const phoneInput = ref(null);

/** Don't offer dates in the past. */
const today = computed(() => new Date().toISOString().slice(0, 10));

const treatments = [
    'General check-up',
    'Pain or emergency',
    'Dental implants',
    'Invisible aligners',
    'Smile design',
    'Jaw joint (TMD)',
    "Kids' dentistry",
];

const times = [
    'Morning · 9:30 – 13:00',
    'Afternoon · 13:00 – 17:00',
    'Evening · 17:00 – 19:30',
];

function submit() {
    errors.name = !form.name.trim();
    errors.phone = !form.phone.trim();

    if (errors.name || errors.phone) {
        sent.value = false;
        (errors.name ? nameInput.value : phoneInput.value)?.focus();

        return;
    }

    sent.value = true;

    Object.keys(form).forEach((key) => (form[key] = ''));
    errors.name = false;
    errors.phone = false;
}
</script>

<template>
    <section class="sec contact" id="contact" ref="root">
        <div class="wrap">
            <div class="c-head">
                <span class="eyebrow" data-rv>Get in touch</span>
                <h2 class="dis" data-rv style="--d:.06s">Book your visit, <em>in a minute.</em></h2>
            </div>

            <div class="c-grid">

                <div class="map-card" data-rv>
                    <iframe
                        title="Map showing Dr. Pushpa Patel's Dental Clinic, Linking Road, Bandra West, Mumbai"
                        src="https://maps.google.com/maps?q=Linking%20Road%2C%20Bandra%20West%2C%20Mumbai&amp;z=15&amp;output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>

                <div class="form-card" id="book" data-rv style="--d:.08s">
                    <h3>Request an appointment</h3>
                    <p>Send this and the front desk will call you back the same working day. Nothing is confirmed until you have spoken to a person.</p>

                    <form class="cform" novalidate @submit.prevent="submit">
                        <div class="f2">
                            <div class="fld" :class="{ err: errors.name }">
                                <label for="c-name">Full name <i>*</i></label>
                                <div class="box">
                                    <input
                                        id="c-name"
                                        ref="nameInput"
                                        v-model="form.name"
                                        type="text"
                                        autocomplete="name"
                                        placeholder="Your name"
                                        @input="errors.name = false"
                                    >
                                </div>
                                <span class="msg">Please tell us your name.</span>
                            </div>
                            <div class="fld" :class="{ err: errors.phone }">
                                <label for="c-phone">Phone number <i>*</i></label>
                                <div class="box">
                                    <input
                                        id="c-phone"
                                        ref="phoneInput"
                                        v-model="form.phone"
                                        type="tel"
                                        autocomplete="tel"
                                        placeholder="+91 98200 00000"
                                        @input="errors.phone = false"
                                    >
                                </div>
                                <span class="msg">A number we can reach you on.</span>
                            </div>
                        </div>

                        <div class="f2">
                            <div class="fld">
                                <label for="c-email">Email <span style="opacity:.6">(optional)</span></label>
                                <div class="box">
                                    <input id="c-email" v-model="form.email" type="email" autocomplete="email" placeholder="you@example.com">
                                </div>
                            </div>
                            <div class="fld">
                                <label for="c-treat">What is it about?</label>
                                <div class="box">
                                    <select id="c-treat" v-model="form.treatment">
                                        <option value="">Choose a treatment</option>
                                        <option v-for="item in treatments" :key="item">{{ item }}</option>
                                    </select>
                                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="f2">
                            <div class="fld">
                                <label for="c-date">Preferred date</label>
                                <div class="box">
                                    <input id="c-date" v-model="form.date" type="date" :min="today">
                                </div>
                            </div>
                            <div class="fld">
                                <label for="c-time">Preferred time</label>
                                <div class="box">
                                    <select id="c-time" v-model="form.time">
                                        <option value="">Any time</option>
                                        <option v-for="item in times" :key="item">{{ item }}</option>
                                    </select>
                                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="fld">
                            <label for="c-msg">Anything you'd like us to know</label>
                            <div class="box">
                                <textarea id="c-msg" v-model="form.message" rows="3" placeholder="Symptoms, past treatment, anything you're anxious about&hellip;"></textarea>
                            </div>
                        </div>

                        <div class="cform-foot">
                            <button class="btn btn-brand" type="submit">Request a call back
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </button>
                            <small>We reply the same working day. Your details are never shared.</small>
                        </div>

                        <div class="c-ok" :class="{ show: sent }" role="status">
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>
                                <b>Thank you &mdash; that's with the front desk.</b>
                                <span>Someone will call you before the end of the day to confirm a time.</span>
                            </span>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</template>
