<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import VTab from "@/Shared/VTab.vue";
import { listTab } from "../../tabs.config.js";
import VForm1Timeline from "@/Shared/ApplicationManagement/VForm1Timeline.vue";
import VForm8ExpenseEstimation from "@/Shared/ManagementFund/VForm8ExpenseEstimation.vue";
import VForm3ProjectCost from "@/Shared/ApplicationManagement/VForm3ProjectCost.vue";
import VForm4Documentation from "@/Shared/ApplicationManagement/VForm4Documentation.vue";
import VForm5Status from "@/Shared/ApplicationManagement/VForm5Status.vue";

const props = defineProps({
    initValue: Object,
    initActiveTab: String,
    refBenefits: Array,
    refProjectCostSeriesDirect: Array,
    arrProjectStatus: Array,
    urlBase: String,
    method: String,
    type: String,
});

const elTab = ref(null);

const activeTab = ref(props.initActiveTab ?? "timeline");

const appBaseUrl = usePage().props.appBaseUrl;

const form = ref({
    timeline: props.initValue?.timeline,
    expenses_estimation: props.initValue?.expenses_estimation,
    project_cost: props.initValue?.expenses_estimation,
    documentation: props.initValue.documentation,
    status: props.initValue.status,
});

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "expenses_estimation":
                return {
                    component: VForm8ExpenseEstimation,
                    additional: {
                        proposalType: props.initValue?.timeline.proposal_type,
                        initValue: form.value.expenses_estimation,
                        researchApproach: form.value.timeline,
                        method: props.method,
                        type: props.type,
                        urlSubmit:
                            appBaseUrl +
                            "/list-of-approved/sub-form/form2/" +
                            props.initValue.timeline.id,
                    },
                };
            case "project_cost":
                return {
                    component: VForm3ProjectCost,
                    additional: {
                        proposalType: 1,
                        initValue: form.value.project_cost,
                        refProjectCostSeriesDirect:
                            props.refProjectCostSeriesDirect,
                        researchApproach: form.value.timeline,
                        exspenseEstimation: form.value.expenses_estimation,
                        method: props.method,
                        type: props.type,
                        urlSubmit:
                            appBaseUrl +
                            "/list-of-approved/sub-form/form3/" +
                            props.initValue.timeline.id,
                    },
                };
            case "documentation":
                return {
                    component: VForm4Documentation,
                    additional: {
                        proposalType: 1,
                        initValue: form.value.documentation,
                        method: props.method,
                        type: props.type,
                        urlSubmit:
                            appBaseUrl +
                            "/list-of-approved/sub-form/form4/" +
                            props.initValue.timeline.id,
                    },
                };
            case "status":
                return {
                    component: VForm5Status,
                    additional: {
                        proposalType: 1,
                        initValue: form.value.status,
                        arrProjectStatus: props.arrProjectStatus,
                        method: props.method,
                        type: props.type,
                        urlSubmit:
                            appBaseUrl +
                            "/list-of-approved/sub-form/form5/" +
                            props.initValue.timeline.id,
                    },
                };
            default:
                return {
                    component: VForm1Timeline,
                    additional: {
                        proposalType: 1,
                        initValue: form.value.timeline,
                        method: props.method,
                        type: props.type,
                        urlSubmit:
                            appBaseUrl +
                            "/list-of-approved/sub-form/form1/" +
                            props.initValue.timeline.id,
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
            <VTab ref="elTab" :listTab="listTab" v-model:value="activeTab" />
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
