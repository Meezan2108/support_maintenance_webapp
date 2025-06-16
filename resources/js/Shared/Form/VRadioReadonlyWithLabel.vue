<script setup>
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
    error: String,
});
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2"
        >
            {{ label }}
        </label>
        <div
            class="col-sm-9 d-flex align-items-center flex-wrap"
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
                    :value="option.id"
                    :checked="option.id == value"
                    disabled
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
