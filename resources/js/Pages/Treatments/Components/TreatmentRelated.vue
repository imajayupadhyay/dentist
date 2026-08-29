<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    relatedTreatments: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <section class="tx-sec">
        <div class="wrap">
            <div class="tx-head" data-rv>
                <span class="eyebrow">Also at the clinic</span>
                <h2 class="dis">Related <em>treatments.</em></h2>
            </div>

            <div class="tx-rel">
                <Link
                    v-for="(item, index) in relatedTreatments"
                    :key="item.url"
                    class="tx-rel-card"
                    :href="item.url"
                    :data-tone="item.tone"
                    data-rv
                    :style="{ '--d': `${index * 0.08}s` }"
                >
                    <figure>
                        <img :src="item.image" :alt="item.alt" loading="lazy" width="1700" height="958">
                    </figure>
                    <div class="body">
                        <h3>{{ item.title }}</h3>
                        <p>{{ item.blurb }}</p>
                        <span class="go">Read more
                            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>
