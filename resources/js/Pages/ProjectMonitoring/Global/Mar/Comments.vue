<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import { listTabMar } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";
import VShow1MilestoneAchievement from "@/Shared/ProjectMonitoring/MarForm/VShow1MilestoneAchievement.vue";
import VShow2ProjectAchievement from "@/Shared/ProjectMonitoring/MarForm/VShow2ProjectAchievement.vue";
import VShow3Commentary from "@/Shared/ProjectMonitoring/MarForm/VShow3Commentary.vue";

import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    urlIndex,
    initValue,
    urlSubmit,
    optionsStatus,
    approvement,
    myApprovement,
} = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label:
            initValue.proposal_type == 1
                ? "Report Progress Temporary Research Fund (TRF)"
                : "Report Progress External Fund",
    },
    {
        url: "#",
        label: "Commenet Milestone Achievement Report (MAR)",
    },
];

const elTab = ref(null);

const activeTab = ref(
    props.additional.initActiveTab ?? "milestone_achievement"
);

const form = useForm({
    milestone_achievement: myApprovement?.comments?.milestone_achievement,
    project_achievement: myApprovement?.comments?.project_achievement,
    commentary: myApprovement?.comments?.commentary,
    status: myApprovement?.status,
    last: false,
    _method: "PUT",
});

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "project_achievement":
                return {
                    component: VShow2ProjectAchievement,
                    additional: {
                        initValue: initValue.project_achievement,
                    },
                };
            case "commentary":
                return {
                    component: VShow3Commentary,
                    additional: {
                        initValue: initValue.commentary,
                    },
                };
            default:
                return {
                    component: VShow1MilestoneAchievement,
                    additional: {
                        initValue: initValue.milestone_achievement,
                    },
                };
        }
    },
});

const isLast = computed(() => {
    return activeTab.value == "commentary";
});

const submitComment = async (comment) => {
    form[activeTab.value] = comment;
    form.last = isLast.value;

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
                        :listTab="listTabMar"
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
