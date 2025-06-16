<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VExpensesTableShow from "./Partials/VExpensesTableShow.vue";

import { computed } from "vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;

const years = computed(() => {
    let startDate = props.additional.researchApproach?.schedule_start_date;
    let duration = props.additional.researchApproach?.schedule_duration;
    if (!startDate || !duration) {
        return [];
    }

    let d = new Date(startDate + "-01");
    let startYear = d.getFullYear();
    d.setMonth(d.getMonth() + parseInt(duration));

    let endYear = d.getFullYear();

    let years = [];

    for (let i = startYear; i <= endYear; i++) {
        years.push(i);
    }

    return years;
});
</script>
<template>
    <h3>Direct Expenses Estimation</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Travel & Transportation (V21000)</h6>
            <VExpensesTableShow
                :value="initValue?.V21000 ?? []"
                title="Travel & Transportation (V21000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Research Materials & Supplies (V26000)</h6>
            <VExpensesTableShow
                :value="initValue?.V26000 ?? []"
                title="Researach Materials & Supplies (V26000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Minor Modifications & Repairs (V28000)</h6>
            <VExpensesTableShow
                :value="initValue?.V28000 ?? []"
                title="Minor Modifications & Repairs (V28000)"
                :years="years"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Special Services (V29000)</h6>
            <VExpensesTableShow
                :value="initValue?.V29000 ?? []"
                title="Special Services (V29000)"
                :years="years"
            />
        </div>
    </div>
</template>
