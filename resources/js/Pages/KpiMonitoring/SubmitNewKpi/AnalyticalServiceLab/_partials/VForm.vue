<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import Swal from "sweetalert2";

import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";
import VListNewFile from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListNewFile.vue";
import VListOldFile from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListOldFile.vue";
import { ref, watch } from "vue";

const props = defineProps({
    initValue: Object,
    projectNumbers: Array,
    urlSubmit: String,
    method: String,
    user: Object,
});

const selFile = ref(null);

const appBaseUrl = usePage().props.appBaseUrl;
const options = [
    {
        id: "",
        description: "Choose Quarter",
    },
    {
        id: 1,
        description: "Quarter 1",
    },
    {
        id: 2,
        description: "Quarter 2",
    },
    {
        id: 3,
        description: "Quarter 3",
    },
    {
        id: 4,
        description: "Quarter 4",
    },
];

const form = useForm({
    date: props.initValue?.date,
    year: props.initValue?.year,
    quarter: props.initValue?.quarter,
    no_sample: props.initValue?.no_sample,
    no_analysis: props.initValue?.no_analysis,
    no_analysis_protocol: props.initValue?.no_analysis_protocol,
    proposal_id: props.initValue?.proposal_id,
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
                <VInputReadonlyWithLabel
                    elId="user_id"
                    label="Project Leader"
                    :value="user?.name"
                    :isPlainText="false"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="year"
                    label="Year"
                    type="number"
                    :error="form.errors.year"
                    v-model:value="form.year"
                    :widthLabel="3"
                    :widthInput="9"
                    :min="1900"
                    :max="new Date().getFullYear()"
                />
            </div>
            <div class="col-6 mb-3">
                <VSelectDefaultWithLabel
                    elId="quarter"
                    label="Quarterly"
                    :error="form.errors.quarter"
                    v-model:value="form.quarter"
                    :widthLabel="3"
                    :widthInput="9"
                    :options="options"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="no_analysis"
                    label="No of Analysis"
                    type="number"
                    :error="form.errors.no_analysis"
                    v-model:value="form.no_analysis"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="no_sample"
                    label="No of Samples"
                    type="number"
                    :error="form.errors.no_sample"
                    v-model:value="form.no_sample"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="no_analysis_protocol"
                    label="No of Analysis Protocol"
                    type="number"
                    :error="form.errors.no_analysis_protocol"
                    v-model:value="form.no_analysis_protocol"
                    :widthLabel="3"
                    :widthInput="9"
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
