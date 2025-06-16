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
    otherOptionLabel: String,
    otherOptionPlaceholder: {
        type: String,
        default: "Other Options ....",
    },
    value: Array,
    error: {
        type: String,
        default: "",
    },
});

const dataValue = ref([]);
const dataOther = ref("");
const isOther = ref(false);

const getOptionValues = (values, options) => {
    return values?.filter((item) => {
        return options.includes(item);
    });
};

const getOtherValue = (values, options) => {
    const others = values?.filter((item) => {
        return !options.includes(item);
    });

    return others?.length > 0 ? others[0] : "";
};

dataValue.value = getOptionValues(props.value, props.options);
dataOther.value = getOtherValue(props.value, props.options);

isOther.value = false;
if (dataOther.value) {
    isOther.value = true;
}
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

            <VCheckboxShow :option="otherOptionLabel" :isChecked="isOther" />

            <div class="form-check" :class="{ 'd-none': !isOther }">
                <input
                    class="form-control form-control-sm mt-1"
                    type="text"
                    :value="dataOther"
                    readonly
                />
            </div>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
