<script setup>
import { ref, watch } from "vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    value: String,
    isProcessing: false,
});

const editorData = ref(props.value);

const emits = defineEmits(["onSubmit"]);

watch(
    () => props.value,
    (newData) => {
        editorData.value = newData;
    }
);

const onSubmit = () => {
    emits("onSubmit", editorData.value);
};
</script>

<template>
    <div class="comment-wrapper">
        <textarea
            v-model="editorData"
            class="form-control comment-textarea"
            :disabled="isProcessing"
        ></textarea>
        <VButtonSubmit
            class="comment-button"
            @onCLickSubmit="onSubmit"
            :isProcessing="isProcessing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>

<style scoped>
.comment-textarea {
    min-height: 120px;
    max-height: 120px;
}

.comment-wrapper {
    position: relative;
}

.comment-button {
    position: absolute;
    right: 10px;
    bottom: 10px;
}

.button-wrapper {
    margin-top: -54px;
}
</style>
