<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalExpenses from "../Modals/VModalExpenses.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

import { sumCost, formatNumber, getIntValue } from "@/Helpers/number.js";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    years: Array,
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const emits = defineEmits(["update:value"]);

const expenses = computed({
    get() {
        let yearsLength = props.years.length;
        return props.value.map((item) => {
            return {
                ...item,
                years: item.years.slice(0, yearsLength),
            };
        });
    },
});

const clickAdd = () => {
    initValue.value = "";
    editedIndex.value = false;
    isShowForm.value = true;
};

const clickEdit = (index) => {
    initValue.value = expenses.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const clickDelete = async (index) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Are you sure?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    emits(
        "update:value",
        expenses.value.filter((item, key) => index != key)
    );
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        expenses.value.push({
            id: "",
            description: value.description,
            years: value.years,
        });
    } else {
        let index = editedIndex.value;
        expenses.value[index].description = value.description;
        expenses.value[index].years = value.years;
    }

    emits("update:value", expenses.value);
    isShowForm.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th class="fw-bold">
                        Expenses
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th v-for="year in years" :key="year" class="fw-bold">
                        {{ year }}
                    </th>
                    <th>Total</th>
                </tr>
                <tr v-for="(item, index) in expenses" :key="item.id">
                    <td>
                        <VButtonIconEdit
                            classStyle="text-warning"
                            @onClick="clickEdit(index)"
                        />
                        <VButtonIconDelete
                            classStyle="text-danger"
                            @onClick="clickDelete(index)"
                        />
                    </td>
                    <td>{{ item.description }}</td>
                    <td v-for="cost in item.years" :key="cost">
                        {{ formatNumber(getIntValue(cost)) }}
                    </td>
                    <td>{{ formatNumber(sumCost(item.years)) }}</td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalExpenses
        v-if="isShowForm"
        :value="initValue"
        :years="years"
        :title="title"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
