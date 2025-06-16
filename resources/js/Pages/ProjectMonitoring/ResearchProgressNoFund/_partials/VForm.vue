<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputStyle2WithLabel from "@/Shared/Form/VInputStyle2WithLabel.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";
import VProjectTeamTable from "@/Shared/ProjectMonitoring/Partials/VProjectTeamTable.vue";
import VObjectivesTable from "@/Shared/ManagementFund/Partials/VObjectivesTable.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import _ from "lodash";

import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";

import VListNewFile from "@/Shared/ProjectMonitoring/ResearchProgress/Partials/VListNewFile.vue";
import VListOldFile from "@/Shared/ProjectMonitoring/ResearchProgress/Partials/VListOldFile.vue";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    initValue: Object,
    projectTitles: Array,
    reportTypes: Array,
    formType: String,
    method: String,
    urlSubmit: String,
});

const selFile = ref(null);

const proposal = ref({});

const appBaseUrl = usePage().props.appBaseUrl;

const form = useForm({
    year: props.initValue?.year,
    ref_report_type_id: props.initValue?.ref_report_type_id,
    ref_pslkm_id: props.initValue?.ref_pslkm_id,
    focus_area: props.initValue?.focus_area,
    issue: props.initValue?.issue,
    strategy: props.initValue?.strategy,
    program: props.initValue?.program,

    project_title: props.initValue?.project_title,
    start_date: props.initValue?.start_date,
    end_date: props.initValue?.end_date,

    project_team: props.initValue?.project_team ?? [],
    objectives: props.initValue?.objective ?? [],

    date: props.initValue?.date,

    background: props.initValue?.background ?? "",
    result: props.initValue?.result ?? "",
    summary: props.initValue?.summary ?? "",

    old_files: props.initValue?.old_files,
    new_files: [],

    is_submited: 0,
    _method: props.method,
});

onMounted(() => {
    if (props.initValue?.proposal_id) {
        populateProposal(props.initValue.proposal_id);
    }
});

const getProposal = async (id) => {
    const response = await axios.get(`${appBaseUrl}/resources/proposal/${id}`);
    return response;
};

const populateProposal = (id) => {
    getProposal(id).then((response) => {
        const { data } = response.data;
        proposal.value = data;
    });
};

const handleClickSubmit = async () => {
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

    form.post(props.urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
        },
    });
};

watch(
    () => form.proposal_id,
    (newValue) => {
        form.milestones_extension = [];
        populateProposal(newValue);
    }
);

watch(selFile, (newValue) => {
    form.new_files.push(newValue);
});
</script>

<template>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="year"
                        label="Year"
                        type="number"
                        v-model:value="form.year"
                        :error="form.errors?.year"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VSelectWithLabel
                        elId="ref_report_type_id"
                        label="Type Of Report"
                        v-model:value="form.ref_report_type_id"
                        :options="reportTypes"
                        :error="form.errors?.ref_report_type_id"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="focus_area"
                        label="Focus Area"
                        v-model:value="form.focus_area"
                        :error="form.errors?.focus_area"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VSelectAsyncWithLabel
                        elId="ref_pslkm_id"
                        label="PSLKM"
                        :error="form.errors.ref_pslkm_id"
                        v-model:value="form.ref_pslkm_id"
                        :url="appBaseUrl + '/resources/pslkm'"
                        :widthLabel="3"
                        :widthInput="9"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="issue"
                        label="Issue"
                        v-model:value="form.issue"
                        :error="form.errors?.issue"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="strategy"
                        label="Strategy"
                        v-model:value="form.strategy"
                        :error="form.errors?.strategy"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputWithLabel
                        elId="program"
                        label="Program"
                        v-model:value="form.program"
                        :error="form.errors?.program"
                    />
                </div>
            </div>
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="mb-4 mt-3">
        <h5>Project</h5>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <VInputWithLabel
                            elId="project_title"
                            label="Project Title"
                            v-model:value="form.project_title"
                            :error="form.errors?.project_title"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <VInputWithLabel
                            elId="start_date"
                            label="Start Date"
                            type="date"
                            v-model:value="form.start_date"
                        />
                    </div>
                    <div class="col-md-6 mb-3">
                        <VInputWithLabel
                            elId="end_date"
                            label="End Date"
                            type="date"
                            v-model:value="form.end_date"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <VProjectTeamTable v-model:value="form.project_team" title="Project Team" />

    <div class="row">
        <div class="col-12 mb-3">
            <VObjectivesTable v-model:value="form.objectives" />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="mb-4">
        <h5>Progress</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <VInputStyle2WithLabel
                    elId="date"
                    label="Date"
                    type="date"
                    v-model:value="form.date"
                    :error="form.errors?.date"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <VContentEditorWithLabel
                    elId="summary"
                    label="Summary"
                    v-model:value="form.summary"
                    :error="form.errors.summary"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <VUploadDocument
                    elId="picture"
                    label="Upload Picture"
                    v-model:value="selFile"
                />
            </div>
            <div class="col-12 mb-3">
                <VListNewFile v-model:value="form.new_files" />
                <VListOldFile v-model:value="form.old_files" />
            </div>
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickSubmit"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>
