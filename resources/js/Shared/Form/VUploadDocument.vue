<script setup>
import { watch, ref } from "vue";
import VueMultiselect from "vue-multiselect";

const props = defineProps({
    elId: String,
    label: String,
    description: String,
    value: Object,
    error: String,
    labelMaxFile: {
        type: String,
        default: "10MB",
    },
});

const picture = ref(null);
const elPicturePreview = ref(null);

const emits = defineEmits(["update:value"]);

watch(picture, (newValue) => {
    emits("update:value", newValue);
});
</script>

<template>
    <div class="">
        <label
            :for="elId"
            class="fw-bold d-flex flex-column align-items-center justify-content-center upload-box text-secondary"
        >
            <span class="material-icons"> cloud_upload </span>

            Browse files to upload
            <span class="fw-normal font-small text-secondary">
                (Support .xlc, .pdf, .doc, .docx)
            </span>
            <span class="fw-normal font-small text-secondary">
                Maximum file size: {{ labelMaxFile }}
            </span>
        </label>
        <input
            type="file"
            :id="elId"
            class="d-none"
            @input="picture = $event.target.files[0]"
        />
    </div>
    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>

<style scoped>
.upload-box {
    height: 200px;
    border: 1px dashed #ccc;
    cursor: pointer;
}

.upload-box .material-icons {
    font-size: 6rem;
}
</style>
