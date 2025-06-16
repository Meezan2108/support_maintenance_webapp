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
    canView,
    arrStatus,
    urlRefTableIndex,
    urlUpdate,
    urlShow,
    urlIndex,
    urlResourcePslkm,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "PSLKM",
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
                        Edit Sub PSLKM
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonInfo v-if="canView" :href="urlShow" />
                    </div>
                </div>
                <VDevider class="mb-4" />
                <VAlert />

                <VForm
                    :initialValue="additional.data"
                    :urlSubmit="urlUpdate"
                    :arrStatus="arrStatus"
                    :urlResourcePslkm="urlResourcePslkm"
                    method="PUT"
                />
            </div>
        </div>
    </div>
</template>
