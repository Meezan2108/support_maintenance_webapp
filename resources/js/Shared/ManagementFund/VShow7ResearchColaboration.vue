<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VIndustriesTableShow from "./Partials/VIndustriesTableShow.vue";
import VInstitutionTableShow from "./Partials/VInstitutionTableShow.vue";
import VProjectTeamTableShow from "./Partials/VProjectTeamTableShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>
<template>
    <h3>Research Collaboration</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Institution Involved in the Project</h6>
            <VInstitutionTableShow :value="initValue.organizations" />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Industries Involved in the Project</h6>
            <VIndustriesTableShow :value="initValue.industries" />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Project Team</h6>
            <VProjectTeamTableShow
                :value="initValue.project_leaders"
                title="Project Leader"
            />

            <VProjectTeamTableShow
                :value="initValue.researchers"
                title="Researcher"
            />

            <VProjectTeamTableShow
                :value="initValue.staffs"
                title="Support Staff Type"
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
