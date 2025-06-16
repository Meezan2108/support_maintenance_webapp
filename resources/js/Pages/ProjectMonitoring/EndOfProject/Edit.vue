<script setup>
import { Head, router } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VForm from "./_partials/VForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    urlIndex,
    urlUpdate,
    initValue,
    initActiveTab,
    projectNumbers,
    questionsBenefit,
} = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Extension of Project",
    },
    {
        url: "#",
        label: "Edit Report",
    },
];
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <VAlert />
        <div
            v-if="projectNumbers.length <= 0"
            class="alert alert-warning fade show"
            role="alert"
        >
            There are no project on going.
        </div>

        <div class="card">
            <div class="card-body">
                <VForm
                    :initValue="initValue"
                    :projectNumbers="projectNumbers"
                    :questionsBenefit="questionsBenefit"
                    :urlSubmit="urlUpdate"
                    :initActiveTab="initActiveTab"
                    formType="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
