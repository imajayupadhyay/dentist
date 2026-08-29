<script setup>
import { ref } from 'vue';
import AccentHeading from './AccentHeading.vue';
import RichText from './RichText.vue';

defineProps({
    treatment: {
        type: Object,
        required: true,
    },
});

const openIndex = ref(null);

function onToggle(event, index) {
    if (event.target.open) {
        openIndex.value = index;
        return;
    }

    if (openIndex.value === index) {
        openIndex.value = null;
    }
}

</script>

<template>
    <section class="tx-sec alt" id="faq" data-section="FAQs">
        <div class="wrap">
            <div class="tx-split">
                <div class="tx-head" data-rv>
                    <span class="eyebrow">{{ treatment.faq_eyebrow }}</span>
                    <AccentHeading
                        tag="h2"
                        class="dis"
                        :text="treatment.faq_heading"
                        :accent="treatment.faq_heading_accent"
                    />
                    <RichText class="lede tx-rich" :html="treatment.faq_lede" />
                </div>

                <div class="tx-faqs" data-rv style="--d:.08s">
                    <details
                        v-for="(faq, index) in treatment.faqs"
                        :key="`${faq.question}-${index}`"
                        class="tx-faq"
                        :open="openIndex === index"
                        @toggle="onToggle($event, index)"
                    >
                        <summary>{{ faq.question }}<span class="sign" aria-hidden="true"></span></summary>
                        <div class="answer">
                            <RichText class="tx-rich" :html="faq.answer" />
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </section>
</template>
