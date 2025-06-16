<script setup>
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import _ from "lodash";
import VCheckboxValueOption2 from "./VCheckboxValueOption2.vue";
import VCheckboxValueOption2Show from "./VCheckboxValueOption2Show.vue";
import VCheckboxShow from "./VCheckboxShow.vue";

const props = defineProps({
    elId: String,
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    options: Array,
    optionValueLabel: Array,
    otherOptionLabel: String,
    otherOptionPlaceholder: {
        type: String,
        default: "Other Options ....",
    },
    value: Array,
    error: String,
});

const emits = defineEmits(["update:value", "onChange"]);

const dataValue = ref([]);
const dataOther = reactive({
    value: "",
    data: ["", ""],
});
const isOther = ref(false);

const findValue = (option) => {
    return (
        dataValue.value?.find((item) => item.value == option) ?? {
            value: "",
            data: ["", ""],
        }
    );
};

watch(
    () => props.value,
    (newValue) => {
        dataValue.value = getOptionValues(newValue, props.options);
        const tempOther = getOtherValue(newValue, props.options);

        isOther.value = tempOther ? true : false;

        Object.assign(
            dataOther,
            tempOther ?? {
                value: "",
                data: ["", ""],
            }
        );
    }
);

const getOptionValues = (values, options) => {
    return values?.filter((item) => options.includes(item?.value));
};

const getOtherValue = (values, options) => {
    const others = values?.filter((item) => {
        return !options.includes(item.value);
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
        data: ["", ""],
    }
);
</script>

<template>
    <div class="">
        <label :for="elId" class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="ms-2">
            <VCheckboxValueOption2Show
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
                    <div class="col-sm-6">
                        <input
                            class="form-control form-control-sm mt-1"
                            :class="{ 'd-none': !isOther }"
                            type="text"
                            :value="dataOther.value"
                            readonly
                        />
                    </div>
                </div>

                <div class="mb-3 row" :class="{ 'd-none': !isOther }">
                    <label for="" class="col-sm-3 col-form-label">
                        {{ optionValueLabel[0] }}
                    </label>
                    <div class="col-sm-3">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="dataOther.data[0]"
                            readonly
                        />
                    </div>
                </div>
                <div class="mb-3 row" :class="{ 'd-none': !isOther }">
                    <label for="" class="col-sm-3 col-form-label">
                        {{ optionValueLabel[1] }}
                    </label>
                    <div class="col-sm-3">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="dataOther.data[1]"
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
