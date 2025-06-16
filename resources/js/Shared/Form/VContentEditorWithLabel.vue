<script setup>
import { ref, watch } from "vue";
import VContentEditor from "@/Shared/Form/VContentEditor.vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    value: String,
    type: {
        type: String,
        default: "text",
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    error: String,
});

const editorData = ref(props.value);

const emits = defineEmits(["update:value"]);

watch(editorData, (newValue) => {
    emits("update:value", newValue);
});
</script>

<template>
    <div class="row">
        <label :for="elId" class="col-12 label-size fw-bold">
            {{ label }}
            <span v-if="isRequired" class="text-danger">*</span>
        </label>
        <div v-if="subLabel" class="col-12">
            <small class="fst-italic">{{ subLabel }}</small>
        </div>
        <div class="col-12 mt-2">
            <VContentEditor
                elId="objectives_content_editor"
                v-model:value="editorData"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="col-sm-12 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
