<script setup>
import { computed, useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    tag: {
        type: String,
        default: 'div',
    },
    html: {
        type: String,
        default: '',
    },
});

const attrs = useAttrs();

const content = computed(() => normalizeHtml(props.html));

function normalizeHtml(value) {
    const html = String(value || '').trim();

    if (! html) {
        return '';
    }

    if (/<[^>]+>/.test(html)) {
        return html;
    }

    return html
        .replace(/\r\n/g, '\n')
        .split(/\n{2,}/)
        .map((paragraph) => paragraph.trim())
        .filter(Boolean)
        .map((paragraph) => `<p>${escapeHtml(paragraph).replace(/\n/g, '<br>')}</p>`)
        .join('');
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>

<template>
    <component :is="tag" v-bind="attrs" v-html="content"></component>
</template>
