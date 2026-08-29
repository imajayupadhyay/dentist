<script setup>
import { nextTick, onMounted, ref, watch } from 'vue';

let richTextId = 0;

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
    minHeight: {
        type: String,
        default: '150px',
    },
});

const emit = defineEmits(['update:modelValue']);

const editor = ref(null);
const focused = ref(false);
const editorId = `rt-${++richTextId}-${props.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`;

onMounted(() => {
    setEditorHtml(props.modelValue);
});

watch(
    () => props.modelValue,
    (value) => {
        if (! focused.value) {
            setEditorHtml(value);
        }
    },
);

function setEditorHtml(value) {
    nextTick(() => {
        if (! editor.value) {
            return;
        }

        const html = normalizeHtml(value);

        if (editor.value.innerHTML !== html) {
            editor.value.innerHTML = html;
        }
    });
}

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

function sync() {
    emit('update:modelValue', editor.value?.innerHTML || '');
}

function run(command, value = null) {
    editor.value?.focus();
    document.execCommand(command, false, value);
    sync();
}

function highlight() {
    run('hiliteColor', '#ffc24d');
}

function clearFormatting() {
    run('removeFormat');
}

function insertLineBreak() {
    editor.value?.focus();
    document.execCommand('insertHTML', false, '<br>');
    sync();
}

function createLink() {
    const selected = window.getSelection()?.toString();
    const href = window.prompt('Enter link URL', selected?.startsWith('http') ? selected : 'https://');

    if (! href) {
        return;
    }

    run('createLink', href);
}

function pastePlainText(event) {
    event.preventDefault();

    const text = event.clipboardData?.getData('text/plain') || '';

    if (! text) {
        return;
    }

    document.execCommand('insertHTML', false, normalizeHtml(text));
    sync();
}
</script>

<template>
    <div class="admin-rich-field">
        <label :for="editorId">{{ label }}</label>

        <div class="admin-rich-editor" :class="{ invalid: error }">
            <div class="admin-rich-toolbar" aria-label="Text formatting">
                <button type="button" title="Bold" aria-label="Bold" @mousedown.prevent="run('bold')"><b>B</b></button>
                <button type="button" title="Italic" aria-label="Italic" @mousedown.prevent="run('italic')"><i>I</i></button>
                <button type="button" title="Highlight" aria-label="Highlight" @mousedown.prevent="highlight">H</button>
                <button type="button" title="Bulleted list" aria-label="Bulleted list" @mousedown.prevent="run('insertUnorderedList')">List</button>
                <button type="button" title="Numbered list" aria-label="Numbered list" @mousedown.prevent="run('insertOrderedList')">1.</button>
                <button type="button" title="New line" aria-label="New line" @mousedown.prevent="insertLineBreak">BR</button>
                <button type="button" title="Link" aria-label="Link" @mousedown.prevent="createLink">Link</button>
                <button type="button" title="Remove formatting" aria-label="Remove formatting" @mousedown.prevent="clearFormatting">Clear</button>
            </div>

            <div
                :id="editorId"
                ref="editor"
                class="admin-rich-surface"
                contenteditable="true"
                :style="{ minHeight }"
                @focus="focused = true"
                @blur="focused = false; sync()"
                @input="sync"
                @paste="pastePlainText"
            ></div>
        </div>

        <small v-if="error">{{ error }}</small>
    </div>
</template>
