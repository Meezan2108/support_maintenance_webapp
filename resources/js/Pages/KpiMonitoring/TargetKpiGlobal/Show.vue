<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import _ from "lodash";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, filters } = props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Global KPI Target",
    },
    {
        url: "#",
        label: "View",
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
                        View Global KPI Target
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="category"
                                    label="Category"
                                    :options="arrCategory"
                                    :value="initValue.category.description"
                                    :isPlainText="false"
                                />
                            </div>

                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="sub_category"
                                    label="Sub Category"
                                    :value="initValue.sub_category?.description"
                                    :isPlainText="false"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="year"
                                    label="Year"
                                    :value="initValue.year"
                                    :isPlainText="false"
                                />
                            </div>

                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="target"
                                    label="Target"
                                    :value="initValue.target"
                                    :isPlainText="false"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
