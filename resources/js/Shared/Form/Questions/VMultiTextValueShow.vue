<script setup>
import { reactive, ref, watch } from "vue";

import VCheckboxValueOptionShow from "./VCheckboxValueOptionShow.vue";
import VCheckboxShow from "./VCheckboxShow.vue";

const props = defineProps({
    elId: String,
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    options: Array,
    optionValueLabel: String,
    otherOptionLabel: String,
    otherOptionPlaceholder: {
        type: String,
        default: "Other Options ....",
    },
    value: Array,
    error: String,
});

const dataValue = ref([]);
const dataOther = reactive({
    value: "",
    data: "",
});
const isOther = ref(false);

const findValue = (option) => {
    return dataValue.value?.find((item) => item.value == option);
};

const getOptionValues = (values, options) => {
    return values?.filter((item) => {
        return options.includes(item?.value);
    });
};

const getOtherValue = (values, options) => {
    const others = values?.filter((item) => {
        return !options.includes(item?.value);
    });

    return others?.length > 0 ? others[0] : false;
};

dataValue.value = getOptionValues(props.value, props.options);
const tempOther = getOtherValue(props.value, props.options);

isOther.value = tempOther ? true : false;

Object.assign(
    dataOther,
    tempOther ?? {
        value: "",
        data: "",
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

            <VCheckboxShow :option="otherOptionLabel" :isChecked="isOther" />
            <div class="form-check">
                <div class="mb-3 row" :class="{ 'd-none': !isOther }">
                    <div class="col-sm-5">
                        <input
                            class="form-control form-control-sm mt-1"
                            type="text"
                            :value="dataOther.value"
                            readonly
                        />
                    </div>
                </div>

                <div class="mb-3 row" :class="{ 'd-none': !isOther }">
                    <label for="" class="col-sm-2 col-form-label">
                        {{ optionValueLabel }}
                    </label>
                    <div class="col-sm-3">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="dataOther.data"
                            readonly
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
