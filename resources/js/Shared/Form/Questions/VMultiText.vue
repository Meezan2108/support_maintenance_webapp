<script setup>
import { ref, watch } from "vue";

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

const emits = defineEmits(["update:value", "onChange"]);

const dataValue = ref([]);
const dataOther = ref("");
const isOther = ref(false);

const emitSelection = () => {
    let data = [...dataValue.value];

    if (isOther.value && dataOther.value) {
        data.push(dataOther.value);
    }

    emits("update:value", data);
    emits("onChange");
};

watch(
    () => props.value,
    (newValue) => {
        dataValue.value = getOptionValues(newValue, props.options);
        dataOther.value = getOtherValue(newValue, props.options);

        isOther.value = false;
        if (dataOther.value) {
            isOther.value = true;
        }
    }
);
watch(dataOther, () => {
    emitSelection();
});

const getOptionValues = (values, options) => {
    return values.filter((item) => {
        return options.includes(item);
    });
};

const getOtherValue = (values, options) => {
    const others = values.filter((item) => {
        return !options.includes(item);
    });

    return others.length > 0 ? others[0] : "";
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
        <div class="">
            <div
                v-for="(option, index) in options"
                :key="option"
                class="form-check"
            >
                <input
                    :id="elId + index"
                    :name="elId"
                    type="checkbox"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    v-model="dataValue"
                    :value="option"
                    @change="emitSelection"
                />
                <label class="form-check-label" :for="elId + index">
                    {{ option }}
                </label>
            </div>

            <div class="form-check">
                <input
                    :id="elId + 'other-checkbox'"
                    :name="elId + 'other-checkbox'"
                    type="checkbox"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    v-model="isOther"
                    value="other"
                    :checked="isOther"
                />
                <label class="form-check-label" :for="elId + 'other-checkbox'">
                    {{ otherOptionLabel }}
                </label>

                <input
                    class="form-control form-control-sm mt-1"
                    :class="{ 'd-none': !isOther }"
                    type="text"
                    v-model="dataOther"
                    @input="emitSelection"
                    :placeholder="otherOptionPlaceholder"
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
