<script setup>
import { listTabEndOfProject } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { Head, useForm } from "@inertiajs/vue3";

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

import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    urlIndex,
    urlSubmit,
    initValue,
    myApprovement,
    approvement,
    optionsStatus,
} = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "End of Project",
    },
    {
        url: "#",
        label: "Comment Report",
    },
];

const elTab = ref(null);

const form = useForm({
    project_details: myApprovement?.comments?.project_details,
    objectives_project: myApprovement?.comments?.objectives_project,
    objectives_achievement: myApprovement?.comments?.objectives_achievement,
    technology: myApprovement?.comments?.technology,
    assessment: myApprovement?.comments?.assessment,
    additional_funding: myApprovement?.comments?.additional_funding,
    benefits: myApprovement?.comments?.benefits,
    report: myApprovement?.comments?.report,

    status: myApprovement?.status,
    last: false,
    is_submited: false,
    _method: "PUT",
});

const activeTab = ref(props.initActiveTab ?? "project_details");

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "objectives_project":
                return {
                    component: VShow2Objectives,
                    additional: {
                        initValue: initValue.project_details?.proposal,
                    },
                };
            case "objectives_achievement":
                return {
                    component: VShow3ObjectivesAchievement,
                    additional: {
                        initValue: initValue.objectives_achievement,
                    },
                };
            case "technology":
                return {
                    component: VShow4Technology,
                    additional: {
                        initValue: initValue.technology,
                    },
                };
            case "assessment":
                return {
                    component: VShow5Assessment,
                    additional: {
                        initValue: initValue.assessment,
                    },
                };
            case "additional_funding":
                return {
                    component: VShow6AdditionalFunding,
                    additional: {
                        initValue: initValue.additional_funding,
                    },
                };
            case "benefits":
                return {
                    component: VShow7Benefits,
                    additional: {
                        initValue: initValue.benefits,
                    },
                };
            case "report":
                return {
                    component: VShow8Report,
                    additional: {
                        initValue: initValue.report,
                    },
                };
            default:
                return {
                    component: VShow1ProjectDetails,
                    additional: {
                        initValue: initValue.project_details,
                    },
                };
        }
    },
});

const isLast = computed(() => {
    return activeTab.value == "report";
});

const submitComment = async (comment) => {
    form[activeTab.value] = comment;
    form.last = isLast.value;

    if (isLast.value) {
        const result = await Swal.fire({
            icon: "warning",
            title: "Do you want to save the changes?",
            showCancelButton: true,
            confirmButtonColor: "#28A745",
            cancelButtonColor: "#dfdfdf",
            confirmButtonText: "Submit Report!",
        });

        form.is_submited = 0;
        if (result.isConfirmed) {
            form.is_submited = 1;
        }
    }

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
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

                    <div v-if="isLast" class="row">
                        <div class="col-md-6 mb-3">
                            <VSelectDefaultWithLabel
                                elId="status"
                                label="Approval Status"
                                v-model:value="form.status"
                                :options="optionsStatus"
                                :error="form.errors.status"
                            />
                        </div>
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
