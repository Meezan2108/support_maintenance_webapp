<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

import VBenefitsTableShow from "./Partials/VBenefitsTableShow.vue";
import VEconomicContributionTableShow from "./Partials/VEconomicContributionTableShow.vue";

const props = defineProps({
    additional: Object,
});

const benefitsOutput = props.additional.refBenefits.filter(
    (item) => item.category == 1
);
const benefitsHuman = props.additional.refBenefits.filter(
    (item) => item.category == 2
);

const { initValue } = props.additional;
</script>
<template>
    <h3>Benefits</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Output Expected from the Project</h6>
            <VBenefitsTableShow
                :benefits="benefitsOutput"
                :value="initValue.output_expected"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Human Capital and Expert Development</h6>
            <VBenefitsTableShow
                :benefits="benefitsHuman"
                detailAs="Specialisation Area (Specific Area)"
                :value="initValue.human_capital"
            />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <VEconomicContributionTableShow
                :value="initValue.economic_contributions"
            />
        </div>
    </div>
</template>

<style scoped>
table th {
    border-color: #dee2e6;
    border-bottom-width: 1px !important;
    text-transform: uppercase;
}
</style>
