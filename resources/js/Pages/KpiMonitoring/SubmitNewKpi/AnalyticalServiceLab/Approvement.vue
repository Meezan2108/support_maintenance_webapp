<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VListOldFileShow from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListOldFileShow.vue";

import _ from "lodash";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, urlApprovement, initValue, filters, file, optionsStatus } =
    props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Analytical Service Lab",
    },
    {
        url: "#",
        label: "Analytical Service Lab Approval",
    },
];

const form = useForm({
    approval_status: initValue.kpi_achievement?.approval_status,
    comment: initValue.kpi_achievement?.comment,
    _method: "PUT",
});

const submit = async (comment) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Approval!",
    });

    form.is_submited = 1;
    if (!result.isConfirmed) {
        form.is_submited = 0;
        return false;
    }

    form.comment = comment;
    form.post(urlApprovement, {
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
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Analytical Service Lab Approval
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="user_id"
                            label="Project Leader"
                            :value="initValue.kpi_achievement?.user?.name"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="year"
                            label="Year"
                            :value="initValue.year"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="quarter"
                            label="Quartery"
                            :value="
                                initValue.quarter
                                    ? 'Quarter ' + initValue.quarter
                                    : ''
                            "
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_analysis"
                            label="No of Analysis"
                            :value="initValue.no_analysis"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_sample"
                            label="No of Sample"
                            :value="initValue.no_sample"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="no_analysis_protocol"
                            label="No of Analysis Protocol"
                            :value="initValue.no_analysis_protocol"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <VListOldFileShow :value="file" />
                    </div>
                </div>

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Approval Status</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <VSelectDefaultWithLabel
                                elId="approval_status"
                                label=""
                                v-model:value="form.approval_status"
                                :options="optionsStatus"
                                :error="form.errors.approval_status"
                                :widthLabel="2"
                                :widthInput="4"
                            />
                        </div>
                    </div>

                    <VTextareaComment
                        @onSubmit="submit"
                        :isProcessing="form.processing"
                        :value="form.comment"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
