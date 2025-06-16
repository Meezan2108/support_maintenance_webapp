<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";

import { ref, watch } from "vue";

import VListNewFile from "./VListNewFile.vue";
import VListOldFile from "./VListOldFile.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import Swal from "sweetalert2";

const props = defineProps({
    initValue: Object,
    urlSubmit: String,
    method: String,
    categoryTypes: Array,
    user: Object,
});

const selFile = ref(null);

const form = useForm({
    submission_date: props.initValue?.submission_date,
    description: props.initValue?.description,
    category: props.initValue?.category,
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
        confirmButtonText: "Submit Documentation!",
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
                    :value="user.name"
                    :isPlainText="false"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="description"
                    label="Description"
                    type="text"
                    :error="form.errors.description"
                    v-model:value="form.description"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <VSelectWithLabel
                    elId="category"
                    label="Category"
                    :error="form.errors.category"
                    v-model:value="form.category"
                    :options="categoryTypes"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
            <div class="col-6 mb-3">
                <VInputWithLabel
                    elId="submission_date"
                    label="Submission Date"
                    type="date"
                    :error="form.errors.submission_date"
                    v-model:value="form.submission_date"
                    :widthLabel="3"
                    :widthInput="9"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VUploadDocument
                    elId="picture"
                    label="Upload File"
                    v-model:value="selFile"
                    labelMaxFile="100MB"
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
