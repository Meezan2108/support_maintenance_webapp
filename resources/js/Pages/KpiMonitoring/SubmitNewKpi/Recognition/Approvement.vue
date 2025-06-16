<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";

import _ from "lodash";
import Swal from "sweetalert2";

import { ref } from "vue";
import VListOldFileShow from "./_partials/VListOldFileShow.vue";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    title: String,
    additional: Object,
});

const selFile = ref(null);

const { urlIndex, urlApprovement, initValue, file, filters, optionsStatus } =
    props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Recognition",
    },
    {
        url: "#",
        label: "Recognition Approval",
    },
];

const form = useForm({
    old_files: file,
    comment: initValue.kpi_achievement?.comment,
    approval_status: initValue.kpi_achievement?.approval_status,
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
                        Recognition Approval
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="date"
                            label="Date"
                            :value="initValue.date"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="recognition"
                            label="Recognition"
                            :value="initValue.recognition"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project"
                            label="Project"
                            :value="initValue.project"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="type"
                            label="Type of Recognition"
                            :value="initValue.recognition_type"
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
                            :value="initValue.kpi_achievement?.user?.name"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project_number"
                            label="Project Number"
                            :value="initValue.proposal?.project_number"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project_title"
                            label="Project Title"
                            :value="initValue.proposal?.project_title"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <VListOldFileShow v-model:value="form.old_files" />
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
