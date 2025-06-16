<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";

import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    data,
    filters,

    arrRepeatType,
    arrCategory,
    urlUpdate,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Reminder",
    },
    {
        url: "#",
        label: "Edit Reminder",
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
                        Edit Reminder
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <VForm
                    :initialValue="data"
                    :arrRepeatType="arrRepeatType"
                    :arrCategory="arrCategory"
                    :urlSubmit="urlUpdate"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
