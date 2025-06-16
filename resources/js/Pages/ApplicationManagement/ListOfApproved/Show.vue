<script setup>
import { Head } from "@inertiajs/vue3";

import VTab from "@/Shared/VTab.vue";
import { listTab } from "../tabs.config.js";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VShow1Timeline from "@/Shared/ApplicationManagement/VShow1Timeline.vue";
import VShow8ExpenseEstimation from "@/Shared/ManagementFund/VShow8ExpenseEstimation.vue";
import VShow9ProjectCost from "@/Shared/ManagementFund/VShow9ProjectCost.vue";
import { computed, ref } from "vue";
import VShow4Documentation from "../../../Shared/ApplicationManagement/VShow4Documentation.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    initValue,
    activeTab: initActiveTab,
    refProjectCostSeriesDirect,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "List of Approved Project",
    },
    {
        url: "#",
        label: "View Proposal",
    },
];

const activeTab = ref(initActiveTab ?? "timeline");

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "expenses_estimation":
                return {
                    component: VShow8ExpenseEstimation,
                    additional: {
                        initValue: initValue.expenses_estimation,
                        researchApproach: initValue.timeline,
                    },
                };
            case "project_cost":
                return {
                    component: VShow9ProjectCost,
                    additional: {
                        initValue: initValue.project_cost,
                        refProjectCostSeriesDirect: refProjectCostSeriesDirect,
                        researchApproach: initValue.timeline,
                        exspenseEstimation: initValue.expenses_estimation,
                    },
                };
            case "documentation":
                return {
                    component: VShow4Documentation,
                    additional: {
                        initValue: initValue.documentation,
                    },
                };
            default:
                return {
                    component: VShow1Timeline,
                    additional: {
                        initValue: initValue.timeline,
                    },
                };
        }
    },
});
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div>
                    <VTab
                        ref="elTab"
                        :listTab="listTab"
                        v-model:value="activeTab"
                    />
                </div>

                <div class="mt-3">
                    <KeepAlive>
                        <component
                            :is="activeComponent.component"
                            :additional="activeComponent.additional"
                        />
                    </KeepAlive>
                </div>
            </div>
        </div>
    </div>
</template>
