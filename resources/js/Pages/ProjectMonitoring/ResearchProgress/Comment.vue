<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputReadonlyStyle2WithLabel from "@/Shared/Form/VInputReadonlyStyle2WithLabel.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";

import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";
import VModalShowObjectives from "@/Shared/ProjectMonitoring/ResearchProgress/VModalShowObjectives.vue";
import VModalShowProjectTeam from "@/Shared/ProjectMonitoring/ResearchProgress/VModalShowProjectTeam.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VListOldFileShow from "@/Shared/ProjectMonitoring/ResearchProgress//Partials/VListOldFileShow.vue";

import VButton from "@/Shared/Buttons/VButton.vue";
import { calcCompletionDate, formatMonth } from "@/Helpers/date.js";

import _ from "lodash";
import { computed, onMounted, ref } from "vue";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    urlIndex,
    urlSubmit,
    initValue,
    approvement,
    myApprovement,
    optionsStatus,
} = props.additional;

const { proposal } = initValue;
const isShowObjectives = ref(false);
const isShowProjectTeam = ref(false);

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Research Progress Report",
    },
    {
        url: "#",
        label: "Show Report",
    },
];

const form = useForm({
    comment: myApprovement?.comments?.comment,
    status: myApprovement?.status,
    is_submited: 0,
    _method: "PUT",
});

const clickShowProjectTeam = (value) => {
    isShowProjectTeam.value = true;
};

const closeModalProjectTeam = () => {
    isShowProjectTeam.value = false;
};

const clickShowObjectives = (value) => {
    isShowObjectives.value = true;
};

const closeModalObjectives = () => {
    isShowObjectives.value = false;
};

const completionDate = computed(() => {
    const startDate = proposal.schedule_start_date;
    const duration = proposal.schedule_duration;

    return calcCompletionDate(startDate, duration);
});

const submitComment = async (comment) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Approval!",
    });

    form.is_submited = 0;
    if (result.isConfirmed) {
        form.is_submited = 1;
    }

    form.comment = comment;

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
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
                <h3>Research Project Report</h3>
                <VDevider class="my-3" />

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="year"
                                    label="Year"
                                    :value="initValue.year"
                                />
                            </div>
                            <div class="col-12 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="ref_report_type_id"
                                    label="Type Of Report"
                                    :value="initValue.report_type?.description"
                                />
                            </div>
                            <div class="col-12 mb-3 order-md-0">
                                <VInputReadonlyWithLabel
                                    elId="focus_area"
                                    label="Focus Area"
                                    :value="initValue.focus_area"
                                />
                            </div>
                            <div class="col-12 mb-3 order-md-0">
                                <VInputReadonlyWithLabel
                                    elId="pslkm"
                                    label="PSLKM"
                                    :value="initValue.pslkm?.description"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 mb-3 order-md-0">
                                <VInputReadonlyWithLabel
                                    elId="issue"
                                    label="Issue"
                                    :value="initValue.issue"
                                />
                            </div>
                            <div class="col-12 mb-3 order-md-0">
                                <VInputReadonlyWithLabel
                                    elId="strategy"
                                    label="Strategy"
                                    :value="initValue.strategy"
                                />
                            </div>
                            <div class="col-12 mb-3 order-md-0">
                                <VInputReadonlyWithLabel
                                    elId="program"
                                    label="Program"
                                    :value="initValue.program"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 mt-3">
                    <h5>Project</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="proposal_id"
                                        label="Project Title"
                                        :value="proposal?.project_title"
                                    />
                                </div>
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="application_id"
                                        label="Application Id"
                                        :value="proposal?.application_id"
                                    />
                                </div>
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="researcher_name"
                                        label="Project Leader"
                                        :value="proposal?.researcher?.name"
                                    />
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="row align-items-sm-center">
                                        <label
                                            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2"
                                        >
                                            Project Team
                                        </label>
                                        <div
                                            class="col-sm-9 custom-position-relative"
                                        >
                                            <VButton
                                                @onClick="clickShowProjectTeam"
                                                :isDisabled="
                                                    _.isEmpty(proposal)
                                                "
                                            >
                                                Show Project Team
                                            </VButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="application_id"
                                        label="Source of Project Funding"
                                        :value="
                                            proposal?.type_of_fund?.description
                                        "
                                    />
                                </div>
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="schedul_start_date"
                                        label="Start Date"
                                        :value="
                                            formatMonth(
                                                proposal?.schedule_start_date
                                            )
                                        "
                                    />
                                </div>
                                <div class="col-12 mb-3">
                                    <VInputReadonlyWithLabel
                                        elId="schedule_end_date"
                                        label="End Date"
                                        :value="completionDate"
                                    />
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="row align-items-sm-center">
                                        <label
                                            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2"
                                        >
                                            Objectives
                                        </label>
                                        <div
                                            class="col-sm-9 custom-position-relative"
                                        >
                                            <VButton
                                                @onClick="clickShowObjectives"
                                                :isDisabled="
                                                    _.isEmpty(proposal)
                                                "
                                            >
                                                Show Objectives
                                            </VButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Progress</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <VInputReadonlyStyle2WithLabel
                                elId="date"
                                label="Date"
                                :value="initValue.date"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <VContentEditorReadonlyWithLabel
                                elId="summary"
                                label="Summary"
                                :value="initValue.summary"
                            />
                        </div>

                        <div class="col-12 mb-3">
                            <VListOldFileShow :value="initValue.old_files" />
                        </div>
                    </div>
                </div>

                <VDevider class="mb-4" />

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Comments</h5>
                    </div>

                    <div class="mb-3">
                        <VTextareaCommentShow
                            :value="approvement"
                            activeTab="comment"
                        />
                    </div>

                    <div class="row">
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
                        :value="form.comment"
                    />
                </div>

                <VModalShowProjectTeam
                    v-if="isShowProjectTeam"
                    :value="proposal.teams"
                    @onCancel="closeModalProjectTeam"
                />

                <VModalShowObjectives
                    v-if="isShowObjectives"
                    :value="proposal.objectives"
                    @onCancel="closeModalObjectives"
                />
            </div>
        </div>
    </div>
</template>
