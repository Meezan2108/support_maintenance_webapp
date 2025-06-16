<script setup>
import { ref } from "vue";
import debounce from "lodash/debounce";

const props = defineProps({
    elId: String,
    value: Object,
    option: String,
    optionValueLabel: Array,
    error: {
        type: String,
        default: "",
    },
});

const emits = defineEmits(["onChangeValue"]);

const optionValue1 = ref(props.value?.data[0] ?? "");
const optionValue2 = ref(props.value?.data[1] ?? "");

const isChecked = ref(props.value?.value == props.option);

const emitSelection = () => {
    let dataEmit = {
        value: props.option,
        data: ["", ""],
        status: false,
    };

    if (isChecked.value) {
        dataEmit = {
            value: props.option,
            data: [optionValue1.value, optionValue2.value],
            status: true,
        };
    }

    emits("onChangeValue", dataEmit);
};

const inputChange = debounce((query) => {
    emitSelection();
}, 200);
</script>

<template>
    <div class="form-check">
        <input
            :id="elId"
            :name="elId"
            type="checkbox"
            class="form-check-input"
            :class="{ 'is-invalid': error }"
            v-model="isChecked"
            :value="option"
            @change="emitSelection"
        />
        <label class="form-check-label" :for="elId">
            {{ option }}
        </label>

        <div class="mb-3 row" :class="{ 'd-none': !isChecked }">
            <label for="" class="col-sm-3 col-form-label">
                {{ optionValueLabel[0] }}
            </label>
            <div class="col-sm-3">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="optionValue1"
                    @input="inputChange"
                />
            </div>
        </div>
        <div class="mb-3 row" :class="{ 'd-none': !isChecked }">
            <label for="" class="col-sm-3 col-form-label">
                {{ optionValueLabel[1] }}
            </label>
            <div class="col-sm-3">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="optionValue2"
                    @input="inputChange"
                />
            </div>
        </div>
    </div>
</template>
