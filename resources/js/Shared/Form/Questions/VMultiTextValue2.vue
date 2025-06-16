<script setup>
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import _ from "lodash";
import VCheckboxValueOption2 from "./VCheckboxValueOption2.vue";

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

const onDataChange = (data) => {
    if (data.status == false) {
        dataValue.value = dataValue.value.filter(
            (item) => data.value != item.value
        );
    } else {
        const index = dataValue.value.findIndex(
            (item) => data.value == item.value
        );

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
    }

    // console.log("change", [...dataValue.value]);
    let dataItem = _.cloneDeep(dataValue.value);

    if (isOther.value) {
        dataItem.push({
            value: dataOther.value,
            data: [...dataOther.data],
        });
    }

    emits(
        "update:value",
        dataItem.map((item) => ({ ...item }))
    );
    emits("onChange");
};

const onOtherDataChange = debounce(() => {
    let dataItem = _.cloneDeep(dataValue.value);

    if (isOther.value) {
        dataItem.push({
            value: dataOther.value,
            data: [...dataOther.data],
        });
    }

    console.log(dataItem);
    emits(
        "update:value",
        dataItem.map((item) => ({ ...item }))
    );
    emits("onChange");
}, 200);

const findValue = (option) => {
    return (
        dataValue.value.find((item) => item.value == option) ?? {
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
    return values.filter((item) => options.includes(item?.value));
};

const getOtherValue = (values, options) => {
    const others = values.filter((item) => {
        return !options.includes(item.value);
    });

    return others.length > 0 ? others[0] : false;
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
        <div class="">
            <div v-for="(option, index) in options" :key="option">
                <VCheckboxValueOption2
                    :elId="elId + index"
                    :option="option"
                    :value="findValue(option)"
                    :optionValueLabel="optionValueLabel"
                    @onChangeValue="onDataChange"
                />
            </div>

            <div class="form-check">
                <input
                    :id="elId + 'other-checkbox'"
                    :name="elId + 'other-checkbox'"
                    type="checkbox"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    v-model="isOther"
                    @change="onOtherDataChange"
                    :checked="isOther"
                    value="other"
                />
                <label class="form-check-label" :for="elId + 'other-checkbox'">
                    {{ otherOptionLabel }}
                </label>

                <div class="mb-3 row" :class="{ 'd-none': !isOther }">
                    <div class="col-sm-6">
                        <input
                            class="form-control form-control-sm mt-1"
                            :class="{ 'd-none': !isOther }"
                            type="text"
                            v-model="dataOther.value"
                            @input="onOtherDataChange"
                            :placeholder="otherOptionPlaceholder"
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
                            v-model="dataOther.data[0]"
                            @input="onOtherDataChange"
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
                            v-model="dataOther.data[1]"
                            @input="onOtherDataChange"
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
