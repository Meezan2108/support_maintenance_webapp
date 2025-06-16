<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { read, utils } from "xlsx";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import { watch } from "vue";
import Swal from "sweetalert2";
import { checkExstension } from "@/Helpers/string";

let props = defineProps({
    title: String,
    additional: Object,
});

const {
    title,
    breadcrumbs,
    header,
    urlSubmit,
    urlIndex,
    urlTemplate,
    filters,
} = props.additional;

const form = useForm({
    file_xls: null,
    file_data: [],
});

watch(
    () => form.file_xls,
    (newValue) => {
        if (!form.file_xls) return false;
        if (!hasExtension("upload-file-bulk", ["xls", "xlsx"])) {
            form.file_xls = null;
            Swal.fire({
                icon: "warning",
                title: "File not allowed!",
                text: "Choose excel file (xls or xlsx)!",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Okay!",
            });
            return false;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const workbook = read(e.target.result);

            const data = utils.sheet_to_json(
                workbook.Sheets[workbook.SheetNames[0]],
                {
                    header: header,
                    raw: true,
                }
            );

            form.file_data = data.filter((value, index) => index > 0);
        };
        reader.readAsArrayBuffer(form.file_xls);
    }
);

const submit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Are you sure?",
        text: "Save Bulk Data!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    form.post(urlSubmit, {
        preserveScroll: true,
        forceFormData: true,
    });
};

function hasExtension(inputID, exts) {
    const fileName = document.getElementById(inputID).value;
    return checkExstension(fileName, exts);
}
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
                        {{ title }}
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <label
                    for="upload-file-bulk"
                    class="fw-bold d-flex flex-column align-items-center justify-content-center upload-box text-secondary py-4"
                >
                    <span class="material-icons"> cloud_upload </span>

                    Browse files to upload
                    <span class="fw-normal font-small text-secondary">
                        (Support .xls, .xlsx)
                    </span>
                    <a :href="urlTemplate" class="btn btn-sm btn-success mt-3"
                        >Download Template</a
                    >
                </label>
                <input
                    type="file"
                    id="upload-file-bulk"
                    class="d-none"
                    @input="form.file_xls = $event.target.files[0]"
                />

                <div class="mt-5">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <caption class="d-none">
                                Bulk Data
                            </caption>
                            <thead>
                                <tr>
                                    <template
                                        v-for="(property, index) in header"
                                        :key="property"
                                    >
                                        <th>{{ property }}</th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in form.file_data"
                                    :key="index"
                                >
                                    <td
                                        v-for="property in header"
                                        :key="index + property"
                                    >
                                        {{ item[property] ?? "" }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-end mt-5">
                    <VButtonSubmit
                        type="button"
                        :isProcessing="form.processing"
                        @onCLickSubmit="submit"
                    >
                        Submit
                    </VButtonSubmit>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.upload-box {
    border: 1px dashed #ccc;
    cursor: pointer;
}

.upload-box .material-icons {
    font-size: 6rem;
}
</style>
