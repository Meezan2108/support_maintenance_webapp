<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import { listTabQfr } from "@/Pages/ProjectMonitoring/tabs.config.js";

import VTab from "@/Shared/VTab.vue";
import { computed, ref } from "vue";

import VShow1ProjectDetails from "@/Shared/ProjectMonitoring/QfrForm/VShow1ProjectDetails.vue";
import VShow2FinancialProgress from "@/Shared/ProjectMonitoring/QfrForm/VShow2FinancialProgress.vue";
import VShow3BudgetVariations from "@/Shared/ProjectMonitoring/QfrForm/VShow3BudgetVariations.vue";
import VShow4ProposedAction from "@/Shared/ProjectMonitoring/QfrForm/VShow4ProposedAction.vue";

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
        label: "Commenet Quarterly Financial Report (QFR)",
    },
];

const elTab = ref(null);

const activeTab = ref(props.additional.initActiveTab ?? "project_details");

const form = useForm({
    project_details: myApprovement?.comments?.project_details,
    financial_progress: myApprovement?.comments?.financial_progress,
    proposed_action: myApprovement?.comments?.proposed_action,
    budget_variations: myApprovement?.comments?.budget_variations,
    status: myApprovement?.status,
    last: false,
    _method: "PUT",
});

const activeComponent = computed({
    get() {
        switch (activeTab.value) {
            case "financial_progress":
                return {
                    component: VShow2FinancialProgress,
                    additional: {
                        initValue: initValue.financial_progress,
                        proposal_id: initValue.project_details?.proposal_id,
                        projectCostSeries: props.additional.projectCostSeries,
                    },
                };
            case "budget_variations":
                return {
                    component: VShow3BudgetVariations,
                    additional: {
                        initValue: initValue.budget_variations,
                    },
                };
            case "proposed_action":
                return {
                    component: VShow4ProposedAction,
                    additional: {
                        initValue: initValue.proposed_action,
                        method: props.method,
                    },
                };
            default:
                return {
                    component: VShow1ProjectDetails,
                    additional: {
                        initValue: initValue.project_details,
                        method: props.method,
                        formType: props.formType,
                    },
                };
        }
    },
});

const isLast = computed(() => {
    return activeTab.value == "proposed_action";
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
                        :listTab="listTabQfr"
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
