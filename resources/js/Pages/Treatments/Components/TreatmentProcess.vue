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
    <section class="tx-sec" id="process" data-section="How it works">
        <div class="wrap">
            <div class="tx-split">
                <div class="tx-head" data-rv>
                    <span class="eyebrow">{{ treatment.process_eyebrow }}</span>
                    <AccentHeading
                        tag="h2"
                        class="dis"
                        :text="treatment.process_heading"
                        :accent="treatment.process_heading_accent"
                    />
                    <RichText class="lede tx-rich" :html="treatment.process_lede" />
                </div>

                <ol class="tx-steps" data-rv style="--d:.08s">
                    <li v-for="(step, index) in treatment.steps" :key="`${step.title}-${index}`" class="tx-step">
                        <span class="n">{{ String(index + 1).padStart(2, '0') }}</span>
                        <div>
                            <div class="tx-step-top">
                                <h3>{{ step.title }}</h3>
                                <span v-if="step.duration" class="meta">{{ step.duration }}</span>
                            </div>
                            <RichText class="tx-rich" :html="step.body" />
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </section>
</template>
