<script setup>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { component as CKEditor } from "@ckeditor/ckeditor5-vue";
import { ref, watch } from "vue";
import { editorConfig } from "./VContentEditor.config.js";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    value: String,
});

const editorData = ref(props.value);

const emits = defineEmits(["update:value"]);

watch(editorData, (newData) => {
    emits("update:value", newData);
});
</script>

<template>
    <CKEditor
        :editor="ClassicEditor"
        v-model="editorData"
        :config="editorConfig"
    />
</template>

<style scoped>
.ck-editor__editable {
    min-height: 150px;
    max-height: 150px;
}
</style>
