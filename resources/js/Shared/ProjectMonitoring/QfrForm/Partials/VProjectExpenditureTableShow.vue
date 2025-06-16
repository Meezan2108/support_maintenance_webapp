<script setup>
import { computed, ref, watch } from "vue";
import { formatNumber, getIntValue } from "@/Helpers/number.js";

import _ from "lodash";
import VProjectExpenditureTableRows from "./VProjectExpenditureTableRows.vue";

const props = defineProps({
    value: {
        type: Array,
    },
});

const emits = defineEmits([
    "update:value",
    "onChangeTotalRecieved",
    "onChangeTotalExpenditure",
]);

const listData = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});

const totalApproved = computed(() => {
    return props.value.reduce((accumulator, object) => {
        return getIntValue(accumulator) + getIntValue(object.total_approved);
    }, 0);
});

const totalRecieved = computed(() => {
    return props.value.reduce((accumulator, object) => {
        return getIntValue(accumulator) + getIntValue(object.total_recieved);
    }, 0);
});

const totalExpenditure = computed(() => {
    return props.value.reduce((accumulator, object) => {
        return getIntValue(accumulator) + getIntValue(object.total_expenditure);
    }, 0);
});

watch(totalRecieved, (newValue) => {
    emits("onChangeTotalRecieved", newValue);
});

watch(totalExpenditure, (newValue) => {
    emits("onChangeTotalExpenditure", newValue);
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td class="fw-bold" width="55%">Project Cost Component</td>
                    <td class="fw-bold text-center" width="15%">
                        Total Approved Budget
                    </td>
                    <td class="fw-bold text-center" with="15%">
                        Total Allocation Received
                    </td>
                    <td class="fw-bold text-center" width="15%">
                        Total Cumulative Expenditure
                    </td>
                </tr>
                <tr v-for="(item, index) in listData" :key="index">
                    <td>{{ item.description }} ({{ item.vseries_code }})</td>
                    <td class="text-end">
                        {{ formatNumber(getIntValue(item.total_approved)) }}
                    </td>
                    <td class="text-end">
                        {{ formatNumber(getIntValue(item.total_recieved)) }}
                    </td>
                    <td class="text-end">
                        {{ formatNumber(getIntValue(item.total_expenditure)) }}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-end">{{ formatNumber(totalApproved) }}</td>
                    <td class="text-end">{{ formatNumber(totalRecieved) }}</td>
                    <td class="text-end">
                        {{ formatNumber(totalExpenditure) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
