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
    <section class="tx-sec alt" id="suitability" data-section="Is it for you?">
        <div class="wrap">
            <div class="tx-head" data-rv>
                <span class="eyebrow">{{ treatment.suitability_eyebrow }}</span>
                <AccentHeading
                    tag="h2"
                    class="dis"
                    :text="treatment.suitability_heading"
                    :accent="treatment.suitability_heading_accent"
                />
                <RichText class="lede tx-rich" :html="treatment.suitability_lede" />
            </div>

            <div class="tx-fit" data-rv style="--d:.08s">
                <div class="yes">
                    <h3>
                        <span class="ic"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg></span>
                        Usually a good fit
                    </h3>
                    <ul>
                        <li v-for="item in treatment.suitable_for" :key="item.text">{{ item.text }}</li>
                    </ul>
                </div>

                <div class="no">
                    <h3>
                        <span class="ic"><svg viewBox="0 0 24 24"><path d="M5 12h14"/></svg></span>
                        We would treat something else first
                    </h3>
                    <ul>
                        <li v-for="item in treatment.not_suitable" :key="item.text">{{ item.text }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
