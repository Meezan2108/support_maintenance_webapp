<script setup>
import { ref } from "vue";

const props = defineProps({
    elId: {
        type: String,
        default: "",
    },
    type: {
        type: String,
        default: "radio",
    },
    label: String,
    value: String,
    options: Array,
    widthLabel: {
        default: 3,
        type: Number,
    },
    widthInput: {
        default: 9,
        type: Number,
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
        <div
            :class="
                'col-sm-' + widthInput + ' d-flex align-items-center flex-wrap'
            "
            style="min-height: 2.5em"
        >
            <div
                v-for="option in options"
                :key="option.id"
                class="form-check form-check-inline"
            >
                <input
                    :id="elId + option.id"
                    :name="elId"
                    :type="type"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    @input="$emit('update:value', $event.target.value)"
                    :value="option.id"
                    :checked="option.id == value"
                />
                <label class="form-check-label" :for="elId + option.id">
                    {{ option.description }}
                </label>
            </div>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
