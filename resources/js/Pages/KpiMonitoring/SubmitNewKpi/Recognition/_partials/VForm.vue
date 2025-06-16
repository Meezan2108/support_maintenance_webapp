<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";
import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";

import { ref, watch } from "vue";

import VListNewFile from "./VListNewFile.vue";
import VListOldFile from "./VListOldFile.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import Swal from "sweetalert2";
import VResearcherInvolvedTable from "../../../../../Shared/KpiMonitoring/VResearcherInvolvedTable.vue";

const props = defineProps({
    initValue: Object,
    urlSubmit: String,
    method: String,
    user: Object,
});

const appBaseUrl = usePage().props.appBaseUrl;

const recognitionTypes = [
    { id: 1, description: "Local" },
    { id: 2, description: "International" },
];

const selFile = ref(null);

const form = useForm({
    date: props.initValue?.date,
    recognition: props.initValue?.recognition,
    type: props.initValue?.type,
    project: props.initValue?.project,
    proposal_id: props.initValue?.proposal_id,
    researchers: props.initValue?.researcher_involved ?? [],
    file_recognition: null,
    new_files: [],
    old_files: props.initValue?.fileable,
    is_submited: false,
    _method: props.method,
});

const submit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Report!",
    });

    form.is_submited = 1;
    if (!result.isConfirmed) {
        form.is_submited = 0;
        return false;
    }

    form.post(props.urlSubmit, {
        preserveScroll: true,
    });
};

watch(selFile, (newValue) => {
    form.new_files.push(newValue);
});
</script>

<template>
    <form @submit.prevent="submit">
        <div class="row">
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="date"
                    label="Date"
                    type="date"
                    :error="form.errors.date"
                    v-model:value="form.date"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="recognition"
                    label="Recognition"
                    type="text"
                    :error="form.errors.recognition"
                    v-model:value="form.recognition"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="project"
                    label="Event"
                    type="text"
                    :error="form.errors.project"
                    v-model:value="form.project"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VSelectWithLabel
                    elId="type"
                    label="Type of Recognition"
                    :error="form.errors.type"
                    v-model:value="form.type"
                    :options="recognitionTypes"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VInputReadonlyWithLabel
                    elId="user_id"
                    label="Project Leader"
                    :value="user.name"
                    :isPlainText="false"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VSelectAsyncWithLabel
                    elId="proposal_id"
                    label="Project Number"
                    :error="form.errors.proposal_id"
                    v-model:value="form.proposal_id"
                    searchBy="project_number,project_title"
                    :url="appBaseUrl + '/resources/proposal'"
                    :filters="{
                        for_kpi: 1,
                    }"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VResearcherInvolvedTable
                    title="Team Member"
                    v-model:value="form.researchers"
                    :is-required="false"
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
        <VDevider class="my-4" />

        <div class="text-end">
            <VButtonSubmit type="submit" :isProcessing="form.processing">
                Submit
            </VButtonSubmit>
        </div>
    </form>
</template>
