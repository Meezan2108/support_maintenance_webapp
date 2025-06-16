<script setup>
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import { computed, ref } from "vue";
import VModalProjectCostSalaries from "../Modals/VModalProjectCostSalaries.vue";
import { formatNumber, sumCost, getIntValue } from "../../../Helpers/number";
const props = defineProps({
    value: Object,
    years: Array,
    isRequired: {
        type: Boolean,
        default: false,
    },
});

const isShowForm = ref(false);
const initValue = ref({});

const emits = defineEmits(["update:value"]);

const expenses = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});

const clickEdit = () => {
    initValue.value = expenses.value;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = expenses.value;
    isShowForm.value = false;
};

const save = (value) => {
    expenses.value.years = value.years;
    isShowForm.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="fw-bold">
                        Staff Category
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th
                        v-for="(year, index) in years"
                        :key="year"
                        class="fw-bold"
                    >
                        <div class="year-count mb-3 text-center">
                            {{ `YEAR ${index + 1} (RM)` }}
                        </div>
                        <div class="year text-center">{{ year }}</div>
                    </th>
                    <th class="text-center">Total (RM)</th>
                </tr>
                <tr>
                    <td>
                        Salaried personnel (V11000)
                        <VButtonIconEdit @onClick="clickEdit(index)" />
                    </td>
                    <td
                        v-for="(year, index) in years"
                        :key="year"
                        class="text-end cost"
                    >
                        {{
                            expenses.years
                                ? formatNumber(
                                      getIntValue(expenses.years[index])
                                  )
                                : 0
                        }}
                    </td>
                    <td class="text-end cost">
                        {{
                            expenses.years
                                ? formatNumber(sumCost(expenses.years))
                                : 0
                        }}
                    </td>
                </tr>
                <tr>
                    <th class="fw-bold footer">Total Salaried</th>
                    <th
                        v-for="(year, index) in years"
                        :key="year"
                        class="fw-bold text-end footer"
                    >
                        {{
                            expenses.years
                                ? formatNumber(
                                      getIntValue(expenses.years[index])
                                  )
                                : 0
                        }}
                    </th>
                    <th class="fw-bold text-end footer">
                        {{
                            expenses.years
                                ? formatNumber(sumCost(expenses.years))
                                : 0
                        }}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <VModalProjectCostSalaries
        v-if="isShowForm"
        :value="initValue"
        :years="years"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>

<style scoped>
table th {
    border-color: #dee2e6;
    border-bottom-width: 1px !important;
    text-transform: uppercase;
}

table th.footer {
    border-bottom-width: 0px !important;
    border-top-width: 1px !important;
}

table td.cost {
    width: 150px;
}
</style>
