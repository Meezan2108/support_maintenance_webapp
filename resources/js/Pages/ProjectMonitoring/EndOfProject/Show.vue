<script setup>
import { listTabEndOfProject } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import { computed, onMounted, ref } from "vue";
import VShow1ProjectDetails from "@/Shared/ProjectMonitoring/EndOfProject/VShow1ProjectDetails.vue";
import VShow2Objectives from "@/Shared/ProjectMonitoring/EndOfProject/VShow2Objectives.vue";
import VShow3ObjectivesAchievement from "@/Shared/ProjectMonitoring/EndOfProject/VShow3ObjectivesAchievement.vue";
import VShow4Technology from "@/Shared/ProjectMonitoring/EndOfProject/VShow4Technology.vue";
import VShow5Assessment from "@/Shared/ProjectMonitoring/EndOfProject/VShow5Assessment.vue";
import VShow6AdditionalFunding from "@/Shared/ProjectMonitoring/EndOfProject/VShow6AdditionalFunding.vue";
import VShow7Benefits from "@/Shared/ProjectMonitoring/EndOfProject/VShow7Benefits.vue";
import VShow8Report from "@/Shared/ProjectMonitoring/EndOfProject/VShow8Report.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, initActiveTab, approvement, questionsBenefit } =
    props.additional;

const { proposal } = initValue;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "End of Project",
    },
    {
        url: "#",
        label: "View Report",
    },
];

const elTab = ref(null);

const form = ref({
    project_details: initValue?.project_details,
    objectives_project: initValue?.objectives_project,
    objectives_achievement: initValue?.objectives_achievement,
    technology: initValue?.technology,
    assessment: initValue?.assessment,
    additional_funding: initValue?.additional_funding,
    benefits: initValue?.benefits,
    report: initValue?.report,
});

const activeTab = ref(initActiveTab ?? "project_details");

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "objectives_project":
                return {
                    component: VShow2Objectives,
                    additional: {
                        initValue: form.value.project_details?.proposal,
                    },
                };
            case "objectives_achievement":
                return {
                    component: VShow3ObjectivesAchievement,
                    additional: {
                        initValue: form.value.objectives_achievement,
                    },
                };
            case "technology":
                return {
                    component: VShow4Technology,
                    additional: {
                        initValue: form.value.technology,
                    },
                };
            case "assessment":
                return {
                    component: VShow5Assessment,
                    additional: {
                        initValue: form.value.assessment,
                    },
                };
            case "additional_funding":
                return {
                    component: VShow6AdditionalFunding,
                    additional: {
                        initValue: form.value.additional_funding,
                    },
                };
            case "benefits":
                return {
                    component: VShow7Benefits,
                    additional: {
                        initValue: form.value.benefits,
                        questionsBenefit: questionsBenefit,
                    },
                };
            case "report":
                return {
                    component: VShow8Report,
                    additional: {
                        initValue: form.value.report,
                    },
                };
            default:
                return {
                    component: VShow1ProjectDetails,
                    additional: {
                        initValue: form.value.project_details,
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
                        :listTab="listTabEndOfProject"
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
                </div>
            </div>
        </div>
    </div>
</template>
