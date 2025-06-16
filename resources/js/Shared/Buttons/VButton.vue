<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
    isDisabled: {
        type: Boolean,
        default: false,
    },
    btnStyle: {
        type: String,
        default: "btn-light",
    },
});

const emits = defineEmits(["onClick"]);

const onClick = () => {
    emits("onClick", true);
};
</script>

<template>
    <button
        :type="type"
        :disabled="isDisabled"
        class="btn rounded-06"
        :class="btnStyle"
        @click="onClick"
    >
        <slot />
    </button>
</template>
