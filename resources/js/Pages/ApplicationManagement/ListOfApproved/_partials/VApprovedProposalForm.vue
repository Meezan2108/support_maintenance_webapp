<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import VTab from "@/Shared/VTab.vue";
import { listTabApproved } from "../../tabs.config.js";
import VForm1Identification from "@/Shared/ManagementFund/VForm1Identification.vue";
import VForm2Objectives from "@/Shared/ManagementFund/VForm2Objectives.vue";
import VForm3ResearchBackground from "@/Shared/ManagementFund/VForm3ResearchBackground.vue";
import VForm4ResearchApproach from "@/Shared/ManagementFund/VForm4ResearchApproach.vue";
import VForm5ProjectSchedule from "@/Shared/ManagementFund/VForm5ProjectSchedule.vue";
import VForm6Benefits from "@/Shared/ManagementFund/VForm6Benefits.vue";
import VForm7ResearchColaboration from "@/Shared/ManagementFund/VForm7ResearchColaboration.vue";
import VForm8ExpenseEstimation from "@/Shared/ManagementFund/VForm8ExpenseEstimation.vue";
import VForm9ProjectCost from "@/Shared/ManagementFund/VForm9ProjectCost.vue";

const props = defineProps({
    proposalType: Number,
    initValue: Object,
    initActiveTab: String,
    refBenefits: Array,
    refProjectCostSeriesDirect: Array,
    urlBase: String,
    urlSubmit: String,
    method: String,
    type: String,
});

let baseUrl = "";
const elTab = ref(null);

const activeTab = ref(props.initActiveTab ?? "identification");

const appBaseUrl = usePage().props.appBaseUrl;

const form = ref({
    identification: props.initValue?.identification,
    objectives: props.initValue?.objectives,
    research_background: props.initValue?.research_background,
    research_approach: props.initValue?.research_approach,
    project_schedule: null,
    benefits: props.initValue?.benefits,
    research_collabration: props.initValue?.research_collabration,
    expenses_estimation: props.initValue?.expenses_estimation,
    project_cost: props.initValue?.expenses_estimation,
});

const urlByProposalType =
    props.proposalType == 1
        ? appBaseUrl + "/trf"
        : appBaseUrl + "/external-fund";

const formatUrl = (type, baseUrl, id) => {
    const url = type == "create" ? baseUrl : baseUrl + "/" + id;

    return url + "?by_rmc=1";
};

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "objectives":
                baseUrl = urlByProposalType + "/sub-form/form2";
                return {
                    component: VForm2Objectives,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.objectives,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "research_background":
                baseUrl = urlByProposalType + "/sub-form/form3";
                return {
                    component: VForm3ResearchBackground,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.research_background,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "research_approach":
                baseUrl = urlByProposalType + "/sub-form/form4";
                return {
                    component: VForm4ResearchApproach,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.research_approach,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "project_schedule":
                return {
                    component: VForm5ProjectSchedule,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.project_schedule,
                        researchApproach: form.value.research_approach,
                        method: props.method,
                        type: props.type,
                    },
                };
            case "benefits":
                baseUrl = urlByProposalType + "/sub-form/form6";
                return {
                    component: VForm6Benefits,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.benefits,
                        refBenefits: props.refBenefits,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "research_collaboration":
                baseUrl = urlByProposalType + "/sub-form/form7";
                return {
                    component: VForm7ResearchColaboration,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.research_collabration,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "expenses_estimation":
                baseUrl = urlByProposalType + "/sub-form/form8";
                return {
                    component: VForm8ExpenseEstimation,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.expenses_estimation,
                        researchApproach: form.value.research_approach,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "project_cost":
                baseUrl = urlByProposalType + "/sub-form/form9";
                return {
                    component: VForm9ProjectCost,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.project_cost,
                        refProjectCostSeriesDirect:
                            props.refProjectCostSeriesDirect,
                        researchApproach: form.value.research_approach,
                        exspenseEstimation: form.value.expenses_estimation,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            default:
                baseUrl = urlByProposalType + "/sub-form/form1";
                return {
                    component: VForm1Identification,
                    additional: {
                        proposalType: props.proposalType,
                        initValue: form.value.identification,
                        method: props.method,
                        type: props.type,
                        urlSubmit: formatUrl(
                            props.type,
                            baseUrl,
                            props.initValue?.id
                        ),
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
    <form @submit.prevent="submit">
        <div>
            <VTab
                ref="elTab"
                :listTab="listTabApproved"
                v-model:value="activeTab"
            />
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
    </form>
</template>
