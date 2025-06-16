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
    additional: Object
});

const {
    area,
    groups,
    filters,
    canView,

    urlRefTableIndex,
    urlUpdate,
    urlShow,
    urlIndex,
} = props.additional;

const breadcrumbs = [
    {
        url: urlRefTableIndex,
        label: "Reference Table Management",
    },
    {
        url: urlIndex,
        label: "For Area",
    },
    {
        url: "#",
        label: "Edit FOR Area",
    },
];

const initialValue = {
    code: area.code,
    description: area.description,
    ref_for_group_id: area.ref_for_group_id,
};
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>
    ß
    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink :href="urlIndex" :filters="filters ?? {}">
                        Edit FOR Area
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonInfo v-if="canView" :href="urlShow" />
                    </div>
                </div>
                <VDevider class="mb-4" />
                <VAlert />
                <VForm :initialValue="initialValue" :urlSubmit="urlUpdate" method="PUT" :groups="groups" />
            </div>
        </div>
    </div>
</template>
