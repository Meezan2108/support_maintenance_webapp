<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
    label: {
        type: String,
        default: "",
    },
    classStyle: {
        type: String,
        default: "btn-default",
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
        class="btn btn-xs"
        :class="classStyle"
        :title="label"
        @click="onClick"
    >
        <span class="material-icons"> delete </span>
    </button>
</template>

<style scoped>
.btn > .material-icons {
    margin: 0px !important;
    font-size: 20px;
}
</style>
