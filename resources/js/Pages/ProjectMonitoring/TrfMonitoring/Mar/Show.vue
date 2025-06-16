<script setup>
import { Head, router } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import { listTabMar } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";
import VShow1MilestoneAchievement from "@/Shared/ProjectMonitoring/MarForm/VShow1MilestoneAchievement.vue";
import VShow2ProjectAchievement from "@/Shared/ProjectMonitoring/MarForm/VShow2ProjectAchievement.vue";
import VShow3Commentary from "@/Shared/ProjectMonitoring/MarForm/VShow3Commentary.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";
import VShow4Attachment from "@/Shared/ProjectMonitoring/MarForm/VShow4Attachment.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, approvement } = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Report Progress Temporary Research Fund (TRF)",
    },
    {
        url: "#",
        label: "View Milestone Achievement Report (MAR)",
    },
];

const elTab = ref(null);

const activeTab = ref(
    props.additional.initActiveTab ?? "milestone_achievement"
);

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
            case "attachment":
                return {
                    component: VShow4Attachment,
                    additional: {
                        initValue: initValue.attachment,
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
                </div>
            </div>
        </div>
    </div>
</template>
