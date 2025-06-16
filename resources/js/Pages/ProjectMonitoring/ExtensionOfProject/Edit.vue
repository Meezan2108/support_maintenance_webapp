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

const { urlIndex, urlUpdate, initValue, projectNumbers } = props.additional;

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
                <h3>Extension of Project Application</h3>
                <VDevider class="my-3" />

                <VForm
                    :initValue="initValue"
                    :projectNumbers="projectNumbers"
                    :urlSubmit="urlUpdate"
                    formType="edit"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
