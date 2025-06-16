<script setup>
import { ref, watch } from "vue";
import VTableRow from "./VTableRow.vue";

const props = defineProps({
    elId: String,
    label: String,
    options: Array,
    value: Array,
    error: {
        type: String,
        default: "",
    },
});

const emits = defineEmits(["update:value", "onChange"]);
const dataValue = ref(props.value ?? []);

const addRow = () => {
    const newArray = new Array(props.options.length ?? 0).fill("");
    dataValue.value.push(newArray);
};

const removeRow = (selIndex) => {
    dataValue.value = dataValue.value
        .filter((item, index) => index != selIndex)
        .map((item) => [...item]); // convert Proxy(array) to plain array

    emits("update:value", dataValue.value);
    emits("onChange");
};

const changeRow = (value, index) => {
    dataValue.value[index] = value;

    emits("update:value", dataValue.value);
    emits("onChange");
};
</script>

<template>
    <div class="">
        <label class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td v-for="option in options" :key="option">
                            {{ option }}
                        </td>
                        <td>#</td>
                    </tr>
                </thead>

                <tbody>
                    <VTableRow
                        v-for="(row, index) in dataValue"
                        :key="index"
                        :index="index"
                        :options="options"
                        :value="row"
                        :error="''"
                        @onChange="changeRow"
                        @onDelete="removeRow"
                    />
                    <tr>
                        <td :colspan="options.length + 1" class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-primary bg-primary rounded-06 px-5 my-2"
                                @click="addRow"
                            >
                                Add
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
