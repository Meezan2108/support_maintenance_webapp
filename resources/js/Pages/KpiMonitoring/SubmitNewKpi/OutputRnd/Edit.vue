<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";
import VHeaderButtonInfo from "@/Shared/HeaderButton/VButtonInfo.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    filters,
    user,
    initValue,
    outputTypes,
    outputStatuses,
    projectNumbers,
    urlUpdate,
    urlIndex,
    urlShow,
    canView,
} = props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "R&D Output",
    },
    {
        url: "#",
        label: "Edit",
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
                        Edit R&D Output
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonInfo v-if="canView" :href="urlShow" />
                    </div>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <VForm
                    :initValue="initValue"
                    :urlSubmit="urlUpdate"
                    :user="user"
                    :outputTypes="outputTypes"
                    :outputStatuses="outputStatuses"
                    :projectNumbers="projectNumbers"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
