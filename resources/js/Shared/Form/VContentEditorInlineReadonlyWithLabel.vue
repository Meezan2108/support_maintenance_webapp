<script setup>
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
    error: String,
});
</script>

<template>
    <div class="row">
        <label
            :for="elId"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size text-sm-end fw-bold mb-sm-0 mb-2 mt-md-2'
            "
        >
            {{ label }}
        </label>
        <div :class="'col-sm-' + widthInput + ' custom-position-relative'">
            <div
                v-html="value"
                class="border border-secondary content-editor-show p-2"
                style="min-height: 50px"
            ></div>
        </div>
    </div>
</template>
