<script setup>
import { ref, watch } from "vue";

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
    value: Boolean,
    error: String,
});

const isChecked = ref(props.value);

const emits = defineEmits(["update:value"]);

watch(isChecked, () => {
    emits("update:value", isChecked);
});
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative"
        >
            Status
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div class="col-sm-9">
            <div class="form-check form-switch col-form-label">
                <input
                    :id="elId"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    type="checkbox"
                    v-model="isChecked"
                    value="1"
                    style="transform: scale(1.2)"
                />
                <label class="form-check-label" for="status">
                    {{ value ? "Active" : "Non-Active" }}
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
