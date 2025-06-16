<script setup>
import { listTabEndOfProject } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

import VForm1ProjectDetails from "@/Shared/ProjectMonitoring/EndOfProject/VForm1ProjectDetails.vue";
import VForm2Objectives from "@/Shared/ProjectMonitoring/EndOfProject/VForm2Objectives.vue";
import VForm3ObjectivesAchievement from "@/Shared/ProjectMonitoring/EndOfProject/VForm3ObjectivesAchievement.vue";
import VForm4Technology from "@/Shared/ProjectMonitoring/EndOfProject/VForm4Technology.vue";
import VForm5Assessment from "@/Shared/ProjectMonitoring/EndOfProject/VForm5Assessment.vue";
import VForm6AdditionalFunding from "@/Shared/ProjectMonitoring/EndOfProject/VForm6AdditionalFunding.vue";
import VForm7Benefits from "@/Shared/ProjectMonitoring/EndOfProject/VForm7Benefits.vue";
import VForm8Report from "@/Shared/ProjectMonitoring/EndOfProject/VForm8Report.vue";

const props = defineProps({
    initActiveTab: String,
    initValue: Object,
    projectNumbers: Array,
    questionsBenefit: Object,
    formType: String,
    method: String,
});

const elTab = ref(null);

const form = ref({
    project_details: props.initValue?.project_details,
    objectives_project: props.initValue?.objectives_project,
    objectives_achievement: props.initValue?.objectives_achievement,
    technology: props.initValue?.technology,
    assessment: props.initValue?.assessment,
    additional_funding: props.initValue?.additional_funding,
    benefits: props.initValue?.benefits,
    report: props.initValue?.report,
});

const appBaseUrl = usePage().props.appBaseUrl;

let baseUrl = "";

const activeTab = ref(props.initActiveTab ?? "project_details");

const formatUrl = (type, baseUrl, id) => {
    return type == "create"
        ? appBaseUrl + baseUrl
        : appBaseUrl + baseUrl + "/" + id;
};

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "objectives_project":
                baseUrl = "/end-of-project/sub-form/form2";
                return {
                    component: VForm2Objectives,
                    additional: {
                        initValue: form.value.project_details?.proposal,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "objectives_achievement":
                baseUrl = "/end-of-project/sub-form/form3";
                return {
                    component: VForm3ObjectivesAchievement,
                    additional: {
                        initValue: form.value.objectives_achievement,
                        proposal_id: form.value.project_details?.proposal_id,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "technology":
                baseUrl = "/end-of-project/sub-form/form4";
                return {
                    component: VForm4Technology,
                    additional: {
                        initValue: form.value.technology,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "assessment":
                baseUrl = "/end-of-project/sub-form/form5";
                return {
                    component: VForm5Assessment,
                    additional: {
                        initValue: form.value.assessment,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "additional_funding":
                baseUrl = "/end-of-project/sub-form/form6";
                return {
                    component: VForm6AdditionalFunding,
                    additional: {
                        initValue: form.value.additional_funding,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "benefits":
                baseUrl = "/end-of-project/sub-form/form7";
                return {
                    component: VForm7Benefits,
                    additional: {
                        initValue: form.value.benefits,
                        questionsBenefit: props.questionsBenefit,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "report":
                baseUrl = "/end-of-project/sub-form/form8";
                return {
                    component: VForm8Report,
                    additional: {
                        initValue: form.value.report,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            default:
                baseUrl = "/end-of-project/sub-form/form1";
                return {
                    component: VForm1ProjectDetails,
                    additional: {
                        initValue: form.value.project_details,
                        method: props.method,
                        projectNumbers: props.projectNumbers ?? [],
                        formType: props.formType,
                        urlSubmit: formatUrl(
                            props.formType,
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
</template>
