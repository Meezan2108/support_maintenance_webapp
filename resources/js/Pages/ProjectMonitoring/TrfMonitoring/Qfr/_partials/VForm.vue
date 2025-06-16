<script setup>
import { listTabQfr } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";

import VForm1ProjectDetails from "@/Shared/ProjectMonitoring/QfrForm/VForm1ProjectDetails.vue";
import VForm2FinancialProgress from "@/Shared/ProjectMonitoring/QfrForm/VForm2FinancialProgress.vue";
import VForm3BudgetVariations from "@/Shared/ProjectMonitoring/QfrForm/VForm3BudgetVariations.vue";
import VForm4ProposedAction from "@/Shared/ProjectMonitoring/QfrForm/VForm4ProposedAction.vue";

const props = defineProps({
    initActiveTab: String,
    initValue: Object,
    projectNumbers: Array,
    projectCostSeries: Array,
    formType: String,
    method: String,
});

const elTab = ref(null);

const form = ref({
    project_details: props.initValue?.project_details,
    financial_progress: props.initValue?.financial_progress,
    budget_variations: props.initValue?.budget_variations,
    proposed_action: props.initValue?.proposed_action,
});

let baseUrl = "";

const activeTab = ref(props.initActiveTab ?? "project_details");

const formatUrl = (type, baseUrl, id) => {
    return type == "create" ? baseUrl : baseUrl + "/" + id;
};

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "financial_progress":
                baseUrl = "/monitoring-trf/qfr/sub-form/form2";
                return {
                    component: VForm2FinancialProgress,
                    additional: {
                        initValue: form.value.financial_progress,
                        proposal_id: form.value.project_details?.proposal_id,
                        projectCostSeries: props.projectCostSeries,
                        method: props.method,
                        formType: props.formType,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "budget_variations":
                baseUrl = "/monitoring-trf/qfr/sub-form/form3";
                return {
                    component: VForm3BudgetVariations,
                    additional: {
                        initValue: form.value.budget_variations,
                        method: props.method,
                        formType: props.formType,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "proposed_action":
                baseUrl = "/monitoring-trf/qfr/sub-form/form4";
                return {
                    component: VForm4ProposedAction,
                    additional: {
                        initValue: form.value.proposed_action,
                        method: props.method,
                        formType: props.formType,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            default:
                activeTab.value = "project_details";
                return {
                    component: VForm1ProjectDetails,
                    additional: {
                        initValue: form.value.project_details,
                        method: props.method,
                        projectNumbers: props.projectNumbers ?? [],
                        formType: props.formType,
                        urlSubmit: "",
                    },
                };
        }
    },
});

const handleOnNext = (value) => {
    form.value[activeTab.value] = value;
    elTab.value.handleClickNavTab(1);
};

const handleOnPrev = (value) => {
    form.value[activeTab.value] = value;
    elTab.value.handleClickNavTab(-1);
};
</script>

<template>
    <div>
        <VTab ref="elTab" :listTab="listTabQfr" v-model:value="activeTab" />
    </div>

    <div class="mt-3">
        <KeepAlive>
            <component
                :is="activeComponent.component"
                :additional="activeComponent.additional"
                @onNext="handleOnNext"
                @onPrev="handleOnPrev"
            />
        </KeepAlive>
    </div>
</template>
