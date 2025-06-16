<script setup>
const props = defineProps({
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    value: String,
});
</script>

<template>
    <div class="row">
        <label class="col-12 label-size fw-bold">
            {{ label }}
        </label>
        <div v-if="subLabel" class="col-12">
            <small class="fst-italic">{{ subLabel }}</small>
        </div>
        <div class="col-12 mt-2">
            <div
                v-html="value"
                class="border border-secondary content-editor-show p-2"
            ></div>
        </div>
    </div>
</template>

<style scoped>
.content-editor-show {
    max-height: 150px;
    min-height: 50px;
    overflow-y: auto;
}
</style>
