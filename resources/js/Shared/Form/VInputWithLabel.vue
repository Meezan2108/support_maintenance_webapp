<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    value: String | Number,
    type: {
        type: String,
        default: "text",
    },
    max: {
        type: String,
        default: null,
    },
    min: {
        type: String,
        default: null,
    },
    unit: {
        type: String,
        default: "",
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

defineEmits(["update:value"]);
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative'
            "
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div :class="'col-sm-' + widthInput + ' custom-position-relative'">
            <input
                :id="elId"
                :type="type"
                class="form-control"
                @input="$emit('update:value', $event.target.value)"
                :value="value"
                :class="{ 'is-invalid': error }"
                :min="min"
                :max="max"
            />

            <span v-if="unit != ''" class="custom-input-unit">{{ unit }}</span>
        </div>
    </div>
    <div v-if="error" class="row">
        <div
            :class="
                'col-sm-' +
                widthInput +
                ' offset-sm-' +
                widthLabel +
                ' text-danger font-error'
            "
        >
            {{ error }}
        </div>
    </div>
</template>
