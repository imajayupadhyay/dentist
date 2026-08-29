<script setup>
import { computed, useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    tag: {
        type: String,
        default: 'h2',
    },
    text: {
        type: String,
        required: true,
    },
    accent: {
        type: String,
        default: '',
    },
});

const attrs = useAttrs();

const parts = computed(() => {
    const text = props.text || '';
    const accent = props.accent?.trim();

    if (! accent) {
        return [{ text, accent: false }];
    }

    const index = text.toLowerCase().indexOf(accent.toLowerCase());

    if (index === -1) {
        return [{ text, accent: false }];
    }

    return [
        { text: text.slice(0, index), accent: false },
        { text: text.slice(index, index + accent.length), accent: true },
        { text: text.slice(index + accent.length), accent: false },
    ].filter((part) => part.text.length > 0);
});
</script>

<template>
    <component :is="tag" v-bind="attrs">
        <template v-for="(part, index) in parts" :key="`${part.text}-${index}`">
            <em v-if="part.accent">{{ part.text }}</em>
            <template v-else>{{ part.text }}</template>
        </template>
    </component>
</template>
