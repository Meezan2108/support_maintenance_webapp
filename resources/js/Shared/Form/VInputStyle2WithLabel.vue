<script setup>
import { ref } from "vue";

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
    unit: {
        type: String,
        default: "",
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
    <div class="">
        <label :for="elId" class="form-label label-size fw-bold">
            {{ label }}
            <span v-if="isRequired" class="text-danger">*</span>
        </label>
        <div class="custom-position-relative">
            <input
                :id="elId"
                :type="type"
                class="form-control"
                @input="$emit('update:value', $event.target.value)"
                :value="value"
                :class="{ 'is-invalid': error }"
            />
            <span v-if="unit != ''" class="custom-input-unit">{{ unit }}</span>
        </div>
    </div>
    <div v-if="error">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
