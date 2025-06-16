<script setup>
import { ref } from "vue";
import debounce from "lodash/debounce";
import VCheckboxShow from "./VCheckboxShow.vue";

const props = defineProps({
    elId: String,
    value: Object,
    option: String,
    optionValueLabel: String,
    error: {
        type: String,
        default: "",
    },
});

const emits = defineEmits(["onChangeValue"]);

const optionValue = ref(props.value?.data ?? "");

const isChecked = ref(props.value?.value == props.option);
</script>

<template>
    <VCheckboxShow :option="option" :isChecked="isChecked" />
    <div class="form-check" :class="{ 'd-none': !isChecked }">
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">
                {{ optionValueLabel }}
            </label>
            <div class="col-sm-3">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    :value="optionValue"
                    readonly
                />
            </div>
        </div>
    </div>
</template>
