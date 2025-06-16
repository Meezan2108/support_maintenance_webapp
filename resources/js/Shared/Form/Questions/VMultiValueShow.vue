<script setup>
import { ref, watch } from "vue";
import VCheckboxValueOption from "./VCheckboxValueOption.vue";
import VCheckboxValueOptionShow from "./VCheckboxValueOptionShow.vue";

const props = defineProps({
    elId: String,
    label: String,
    options: Array,
    optionValueLabel: String,
    value: Array,
    error: {
        type: String,
        default: "",
    },
});

const dataValue = ref(props.value ?? []);

const findValue = (option) => {
    return dataValue.value.find((item) => item.value == option);
};

watch(
    () => props.value,
    (newValue) => {
        dataValue.value = newValue;
    }
);
</script>

<template>
    <div class="">
        <label :for="elId" class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="ms-2">
            <VCheckboxValueOptionShow
                v-for="(option, index) in options"
                :key="option"
                :elId="elId + index"
                :option="option"
                :value="findValue(option)"
                :optionValueLabel="optionValueLabel"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
