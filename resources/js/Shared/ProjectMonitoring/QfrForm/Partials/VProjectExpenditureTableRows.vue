<script setup>
import { computed, ref, watch } from "vue";
import { formatNumber, getIntValue } from "@/Helpers/number.js";

const props = defineProps({
    value: {
        type: Array,
    },
    index: Number,
});

const emits = defineEmits(["update:value"]);

const data = ref({
    id: props.value?.id,
    report_quarterly_financial_id: props.value?.report_quarterly_financial_id,
    description: props.value?.description,
    vseries_code: props.value?.vseries_code,
    ref_project_cost_series_id: props.value?.ref_project_cost_series_id,
    total_approved: props.value?.total_approved,
    total_recieved: props.value?.total_recieved,
    total_expenditure: props.value?.total_expenditure,
});

watch(
    () => data.value.total_recieved,
    (newValue) => {
        emits("update:value", data.value);
    }
);

watch(
    () => data.value.total_expenditure,
    (newValue) => {
        emits("update:value", data.value);
    }
);
</script>

<template>
    <tr>
        <td>{{ data.description }} ({{ data.vseries_code }})</td>
        <td>{{ formatNumber(data.total_approved) }}</td>
        <td>
            <input
                type="number"
                class="form-control"
                v-model="data.total_recieved"
            />
        </td>
        <td>
            <input
                type="number"
                class="form-control"
                v-model="data.total_expenditure"
            />
        </td>
    </tr>
</template>
