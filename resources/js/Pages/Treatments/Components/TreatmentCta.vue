<script setup>
import AccentHeading from './AccentHeading.vue';
import RichText from './RichText.vue';

defineProps({
    treatment: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section class="tx-cta" aria-labelledby="tx-cta-h">
        <div class="wrap">
            <div class="tx-cta-in">
                <div data-rv>
                    <AccentHeading
                        id="tx-cta-h"
                        tag="h2"
                        class="dis"
                        :text="treatment.cta_heading"
                        :accent="treatment.cta_heading_accent"
                    />
                    <RichText class="tx-rich" :html="treatment.cta_body" />
                </div>
                <div class="tx-cta-btns" data-rv style="--d:.1s">
                    <a class="btn btn-tone" :href="treatment.whatsapp_url" target="_blank" rel="noopener">
                        <svg class="ic-wa" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.13h-.01a8.23 8.23 0 01-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.36c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 012.41 5.83c0 4.54-3.7 8.24-8.24 8.24z"/><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.38-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.7.63.71.23 1.36.19 1.87.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35z"/></svg>
                        Message on WhatsApp
                    </a>
                    <a class="btn btn-line-tone" :href="treatment.phone_href">
                        <svg class="ic-tel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h3l2 5-2.4 1.4a12 12 0 005 5L14 13l5 2v3a2 2 0 01-2.2 2A16 16 0 013 6.2 2 2 0 015 4z"/></svg>
                        Call {{ treatment.phone }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
