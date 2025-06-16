<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VTab from "@/Shared/VTab.vue";
import { listTab } from "../tabs.config.js";
import VShow1Identification from "@/Shared/ManagementFund/VShow1Identification.vue";
import VShow2Objectives from "@/Shared/ManagementFund/VShow2Objectives.vue";
import VShow3ResearchBackground from "@/Shared/ManagementFund/VShow3ResearchBackground.vue";
import VShow4ResearchApproach from "@/Shared/ManagementFund/VShow4ResearchApproach.vue";

import VShow5ProjectSchedule from "@/Shared/ManagementFund/VShow5ProjectSchedule.vue";
import VShow6Benefits from "@/Shared/ManagementFund/VShow6Benefits.vue";
import VShow7ResearchColaboration from "@/Shared/ManagementFund/VShow7ResearchColaboration.vue";
import VShow8ExpenseEstimation from "@/Shared/ManagementFund/VShow8ExpenseEstimation.vue";
import VShow9ProjectCost from "@/Shared/ManagementFund/VShow9ProjectCost.vue";

import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";

import { computed, ref } from "vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    initValue,
    activeTab: initActiveTab,
    refBenefits,
    refProjectCostSeriesDirect,
    urlSubmit,
    approvement,
    myApprovement,
} = props.additional;

const breadcrumbs = [
    {
        url: props.additional.urlIndex,
        label: "External Fund",
    },
    {
        url: "#",
        label: "Comment Proposal",
    },
];

const elTab = ref(null);

const activeTab = ref(initActiveTab ?? "identification");

const form = useForm({
    identification: myApprovement?.comments?.identification,
    objectives: myApprovement?.comments?.objectives,
    research_background: myApprovement?.comments?.research_background,
    research_approach: myApprovement?.comments?.research_approach,
    project_schedule: myApprovement?.comments?.project_schedule,
    benefits: myApprovement?.comments?.benefits,
    research_collabration: myApprovement?.comments?.research_collabration,
    expenses_estimation: myApprovement?.comments?.expenses_estimation,
    project_cost: myApprovement?.comments?.project_cost,
    last: false,
    _method: "PUT",
});

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "objectives":
                return {
                    component: VShow2Objectives,
                    additional: {
                        initValue: initValue.objectives,
                    },
                };
            case "research_background":
                return {
                    component: VShow3ResearchBackground,
                    additional: {
                        initValue: initValue.research_background,
                    },
                };
            case "research_approach":
                return {
                    component: VShow4ResearchApproach,
                    additional: {
                        initValue: initValue.research_approach,
                    },
                };
            case "project_schedule":
                return {
                    component: VShow5ProjectSchedule,
                    additional: {
                        initValue: initValue.project_schedule,
                        researchApproach: initValue.research_approach,
                    },
                };
            case "benefits":
                return {
                    component: VShow6Benefits,
                    additional: {
                        initValue: initValue.benefits,
                        refBenefits: refBenefits,
                    },
                };
            case "research_collaboration":
                return {
                    component: VShow7ResearchColaboration,
                    additional: {
                        initValue: initValue.research_collabration,
                    },
                };
            case "expenses_estimation":
                return {
                    component: VShow8ExpenseEstimation,
                    additional: {
                        initValue: initValue.expenses_estimation,
                        researchApproach: initValue.research_approach,
                    },
                };
            case "project_cost":
                return {
                    component: VShow9ProjectCost,
                    additional: {
                        initValue: initValue.project_cost,
                        refProjectCostSeriesDirect: refProjectCostSeriesDirect,
                        researchApproach: initValue.research_approach,
                        exspenseEstimation: initValue.expenses_estimation,
                    },
                };
            default:
                return {
                    component: VShow1Identification,
                    additional: {
                        initValue: initValue.identification,
                    },
                };
        }
    },
});

const submitComment = async (comment) => {
    form[activeTab.value] = comment;
    form.last = activeTab.value == "project_cost";
    await form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            elTab.value.handleClickNavTab(1);
        },
    });
};
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

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Comments</h5>
                    </div>

                    <div class="mb-3">
                        <VTextareaCommentShow
                            :value="approvement"
                            :activeTab="activeTab"
                        />
                    </div>

                    <VTextareaComment
                        @onSubmit="submitComment"
                        :isProcessing="form.processing"
                        :value="form[activeTab]"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
