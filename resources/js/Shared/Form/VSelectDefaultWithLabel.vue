<script setup>
const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    label: String,
    value: String,
    options: Array,
    error: String,
});

defineEmits(["update:value"]);
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            v-if="label != ''"
            :for="elId"
            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative"
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div class="col-sm-9">
            <select
                :id="elId"
                class="form-select"
                :class="{ 'is-invalid': error }"
                @input="$emit('update:value', $event.target.value)"
            >
                <option
                    v-for="option in options"
                    :key="option.id"
                    :value="option.id"
                    :selected="option.id == value"
                >
                    {{ option.description }}
                </option>
            </select>
        </div>
    </div>

    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
