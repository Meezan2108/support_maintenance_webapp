<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import { useForm } from "@inertiajs/vue3";

import { ref } from "vue";
import VListOldFileShow from "./_partials/VListOldFileShow.vue";

import _ from "lodash";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, file, filters, method } = props.additional;

const selFile = ref(null);

const form = useForm({
    old_files: file,
    new_files: [],
    is_submited: false,
    _method: method,
});

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Documentation",
    },
    {
        url: "#",
        label: "View Documentation",
    },
];
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
                        View Documentation
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="user_id"
                            label="Project Leader"
                            :value="initValue.project_leader?.name"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="recognition"
                            label="Description"
                            :value="initValue.description"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="category"
                            label="Category"
                            :value="initValue.ref_category.description"
                            :widthLabel="3"
                            :widthInput="9"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="submission_date"
                            label="Submisiion of Date"
                            :value="initValue.submission_date"
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
            </div>
        </div>
    </div>
</template>
