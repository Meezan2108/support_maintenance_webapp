<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    isProcessing: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["onCLickSubmit"]);

const onClick = () => {
    emits("onCLickSubmit", true);
};
</script>

<template>
    <button
        :type="type"
        :disabled="isProcessing"
        class="btn btn-success rounded-06"
        @click="onClick"
    >
        <slot />
        <div
            class="spinner-border spinner-border-sm ms-2"
            role="status"
            v-if="isProcessing"
        >
            <span class="visually-hidden">Loading...</span>
        </div>
    </button>
</template>
