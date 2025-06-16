<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalExpensesShow from "../Modals/VModalExpensesShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";

import { sumCost, formatNumber, getIntValue } from "@/Helpers/number.js";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
    years: Array,
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const clickAdd = () => {
    initValue.value = "";
    editedIndex.value = false;
    isShowForm.value = true;
};

const clickShow = (index) => {
    initValue.value = props.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th class="fw-bold">Expenses</th>
                    <th v-for="year in years" :key="year" class="fw-bold">
                        {{ year }}
                    </th>
                    <th>Total</th>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td>
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.description }}</td>
                    <td v-for="cost in item.years" :key="cost">
                        {{ formatNumber(getIntValue(cost)) }}
                    </td>
                    <td>{{ formatNumber(sumCost(item.years)) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalExpensesShow
        v-if="isShowForm"
        :value="initValue"
        :years="years"
        :title="title"
        @onCancel="cancelForm"
    />
</template>
