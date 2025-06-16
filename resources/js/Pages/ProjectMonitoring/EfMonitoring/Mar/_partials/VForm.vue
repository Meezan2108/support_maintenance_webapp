<script setup>
import { listTabMar } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";
import VForm1MilestoneAchievement from "@/Shared/ProjectMonitoring/MarForm/VForm1MilestoneAchievement.vue";
import VForm2ProjectAchievement from "@/Shared/ProjectMonitoring/MarForm/VForm2ProjectAchievement.vue";
import VForm3Commentary from "@/Shared/ProjectMonitoring/MarForm/VForm3Commentary.vue";
import VForm4Attachment from "@/Shared/ProjectMonitoring/MarForm/VForm4Attachment.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    initActiveTab: String,
    initValue: Object,
    projectNumbers: Array,
    formType: String,
    method: String,
});

const elTab = ref(null);

const form = ref({
    milestone_achievement: props.initValue?.milestone_achievement,
    project_achievement: props.initValue?.project_achievement,
    commentary: props.initValue?.commentary,
    attachment: props.initValue?.attachment,
});

const appBaseUrl = usePage().props.appBaseUrl;

let baseUrl = "";

const activeTab = ref(props.initActiveTab ?? "milestone_achievement");

const formatUrl = (type, baseUrl, id) => {
    console.log(props.method);
    return type == "create" ? baseUrl : baseUrl + "/" + id;
};

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "project_achievement":
                baseUrl = appBaseUrl + "/monitoring-trf/mar/sub-form/form2";
                return {
                    component: VForm2ProjectAchievement,
                    additional: {
                        initValue: form.value.project_achievement,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            case "commentary":
                baseUrl = appBaseUrl + "/monitoring-trf/mar/sub-form/form3";
                return {
                    component: VForm3Commentary,
                    additional: {
                        initValue: form.value.commentary,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };

            case "attachment":
                baseUrl = appBaseUrl + "/monitoring-trf/mar/sub-form/form4";
                return {
                    component: VForm4Attachment,
                    additional: {
                        initValue: form.value.attachment,
                        method: props.method,
                        urlSubmit: formatUrl(
                            props.formType,
                            baseUrl,
                            props.initValue?.id
                        ),
                    },
                };
            default:
                baseUrl = appBaseUrl + "/monitoring-trf/mar/sub-form/form1";
                return {
                    component: VForm1MilestoneAchievement,
                    additional: {
                        initValue: form.value.milestone_achievement,
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
        <VTab ref="elTab" :listTab="listTabMar" v-model:value="activeTab" />
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
