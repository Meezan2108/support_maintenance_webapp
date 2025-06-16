<script setup>
import { computed, ref } from "vue";
import { formatNumber, getIntValue } from "@/Helpers/number.js";

const props = defineProps({
    value: Object,
    years: Array,
});

const isShowForm = ref(false);
const initValue = ref({});

const sumCost = (costYears) => {
    return costYears.reduce((a, b) => parseInt(a) + parseInt(b), 0) ?? 0;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="fw-bold">Staff Category</th>
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
                    <td>Salaried personnel (V11000)</td>
                    <td
                        v-for="(year, index) in years"
                        :key="year"
                        class="text-end cost"
                    >
                        {{
                            value.years
                                ? formatNumber(getIntValue(value.years[index]))
                                : 0
                        }}
                    </td>
                    <td class="text-end cost">
                        {{
                            value.years ? formatNumber(sumCost(value.years)) : 0
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
                            value.years
                                ? formatNumber(getIntValue(value.years[index]))
                                : 0
                        }}
                    </th>
                    <th class="fw-bold text-end footer">
                        {{
                            value.years ? formatNumber(sumCost(value.years)) : 0
                        }}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
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
