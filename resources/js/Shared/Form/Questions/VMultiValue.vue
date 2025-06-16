<script setup>
import { ref, watch } from "vue";
import VCheckboxValueOption from "./VCheckboxValueOption.vue";

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

const emits = defineEmits(["update:value", "onChange"]);

const dataValue = ref(props.value ?? []);

const onDataChange = (data) => {
    if (data.status == false) {
        dataValue.value = dataValue.value.filter(
            (item) => data.value != item.value
        );

        emits("update:value", dataValue.value);
        emits("onChange");
        return;
    }

    const index = dataValue.value.findIndex((item) => data.value == item.value);

    if (index == -1) {
        dataValue.value.push({
            value: data.value,
            data: data.data,
        });
    } else {
        dataValue.value[index] = {
            value: data.value,
            data: data.data,
        };
    }

    emits("update:value", dataValue.value);
    emits("onChange");
};

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
        <div v-for="(option, index) in options" :key="option">
            <VCheckboxValueOption
                :elId="elId + index"
                :option="option"
                :value="findValue(option)"
                :optionValueLabel="optionValueLabel"
                @onChangeValue="onDataChange"
            />
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
