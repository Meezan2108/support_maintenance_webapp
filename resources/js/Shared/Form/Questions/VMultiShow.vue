<script setup>
import { ref, watch } from "vue";
import VCheckboxShow from "./VCheckboxShow.vue";

const props = defineProps({
    elId: String,
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    options: Array,
    value: Array,
    error: String,
});
</script>

<template>
    <div class="">
        <label :for="elId" class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="ms-2">
            <VCheckboxShow
                v-for="(option, index) in options"
                :key="option"
                :option="option"
                :isChecked="value?.includes(option)"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
