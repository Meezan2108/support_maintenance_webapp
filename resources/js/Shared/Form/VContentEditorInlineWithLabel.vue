<script setup>
import { ref, watch } from "vue";
import VContentEditor from "@/Shared/Form/VContentEditor.vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    value: String,
    type: {
        type: String,
        default: "text",
    },
    widthLabel: {
        type: Number,
        default: 3,
    },
    widthInput: {
        type: Number,
        default: 9,
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
        <label
            :for="elId"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size text-sm-end fw-bold mb-sm-0 mb-2 mt-md-2 position-relative'
            "
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div :class="'col-sm-' + widthInput + ' custom-position-relative'">
            <VContentEditor
                elId="objectives_content_editor"
                v-model:value="editorData"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div
            :class="
                'col-sm-' +
                widthLabel +
                ' offset-sm-' +
                widthInput +
                ' text-danger font-error'
            "
        >
            {{ error }}
        </div>
    </div>
</template>
