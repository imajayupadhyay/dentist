<script setup>
import RichText from '@/Components/Global/RichText.vue';
import AccentHeading from './AccentHeading.vue';

defineProps({
    treatment: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section class="tx-sec" id="overview" data-section="Overview">
        <div class="wrap">
            <div class="tx-head" data-rv>
                <span class="eyebrow">{{ treatment.overview_eyebrow }}</span>
                <AccentHeading
                    tag="h2"
                    class="dis"
                    :text="treatment.overview_heading"
                    :accent="treatment.overview_heading_accent"
                />
                <RichText class="lede tx-rich" :html="treatment.overview_lede" />
            </div>

            <div class="tx-overview">
                <RichText class="tx-prose tx-rich" :html="treatment.overview_body" data-rv style="--d:.06s" />

                <figure class="tx-figure" data-rv style="--d:.12s">
                    <div class="shot">
                        <img
                            :src="treatment.overview_image"
                            :alt="treatment.overview_image_alt"
                            loading="lazy"
                            width="1700"
                            height="1133"
                        >
                    </div>
                    <figcaption v-if="treatment.overview_caption">{{ treatment.overview_caption }}</figcaption>
                </figure>
            </div>
        </div>
    </section>
</template>
