<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import Swal from "sweetalert2";
import VInputResearcherInvolved from "@/Shared/KpiMonitoring/VInputResearcherInvolved.vue";
import { USER_ROLE_RESEARCHER } from "@/Config/user.js";

import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";
import VListNewFile from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListNewFile.vue";
import VListOldFile from "@/Pages/KpiMonitoring/SubmitNewKpi/_partials/VListOldFile.vue";
import { ref, watch } from "vue";

const props = defineProps({
    initValue: Object,
    urlSubmit: String,
    method: String,
    publicationTypes: Array,
    projectNumbers: Array,
    user: Object,
});

const selFile = ref(null);

const form = useForm({
    date_published: props.initValue?.date_published,
    title: props.initValue?.title,
    ref_pub_type_id: props.initValue?.ref_pub_type_id,
    proposal_id: props.initValue?.proposal_id,
    publisher: props.initValue?.publisher,
    researchers: props.initValue?.researcher_involved ?? [],
    new_files: [],
    old_files: props.initValue?.fileable,
    is_submited: false,
    _method: props.method,
});

const appBaseUrl = usePage().props.appBaseUrl;

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
            <div class="col-12 mb-3">
                <VInputWithLabel
                    elId="date_published"
                    label="Date"
                    type="date"
                    :error="form.errors.date_published"
                    v-model:value="form.date_published"
                    :widthLabel="2"
                    :widthInput="4"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="user_id"
                    label="Main Author"
                    :value="user.name"
                    :isPlainText="false"
                    :widthLabel="2"
                    :widthInput="4"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <VInputResearcherInvolved
                    ref="elCoAuthor"
                    elId="co-author"
                    label="Co-Author"
                    :error="form.errors?.researchers"
                    v-model:value="form.researchers"
                    searchBy="name"
                    :url="appBaseUrl + '/resources/user'"
                    :filters="{
                        show_all: 1,
                        roles: [USER_ROLE_RESEARCHER],
                    }"
                    :widthLabel="2"
                    :widthInput="7"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VInputWithLabel
                    elId="title"
                    label="Publication (APA Format)"
                    type="text"
                    :error="form.errors.title"
                    v-model:value="form.title"
                    :widthLabel="2"
                    :widthInput="10"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VSelectWithLabel
                    elId="ref_pub_type_id"
                    label="Type of Publication"
                    :error="form.errors.ref_pub_type_id"
                    v-model:value="form.ref_pub_type_id"
                    :options="publicationTypes"
                    :widthLabel="2"
                    :widthInput="4"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VInputWithLabel
                    elId="publisher"
                    label="Publisher"
                    type="text"
                    :error="form.errors.publisher"
                    v-model:value="form.publisher"
                    :widthLabel="2"
                    :widthInput="4"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
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
                    :widthLabel="2"
                    :widthInput="6"
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
