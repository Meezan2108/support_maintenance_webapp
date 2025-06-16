<script setup>
import { ref } from "vue";
import debounce from "lodash/debounce";
import VCheckboxShow from "./VCheckboxShow.vue";

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

const optionValue1 = ref(props.value?.data[0] ?? "");
const optionValue2 = ref(props.value?.data[1] ?? "");

const isChecked = ref(props.value?.value == props.option);
</script>

<template>
    <VCheckboxShow :option="option" :isChecked="isChecked" />
    <div class="form-check" :class="{ 'd-none': !isChecked }">
        <div class="mb-3 row">
            <label for="" class="col-sm-3 col-form-label">
                {{ optionValueLabel[0] }}
            </label>
            <div class="col-sm-3">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="optionValue1"
                    readonly
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
                    readonly
                />
            </div>
        </div>
    </div>
</template>
