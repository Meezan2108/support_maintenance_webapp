<script setup>
import { Head, Link } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";
import VHeaderButtonEdit from "@/Shared/HeaderButton/VButtonEdit.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

const props = defineProps({
    title: String,
    additional: Object
});

const {
    area,
    filters,
    canEdit,

    urlRefTableIndex,
    urlEdit,
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
        label: "Detail FOR Area",
    },
];
</script>

<template>
    <Head>
        <title>Detail - FOR Area</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink :href="urlIndex" :filters="filters ?? {}">
                        Detail FOR Area
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonEdit v-if="canEdit" :href="urlEdit" />
                    </div>
                </div>
                <VDevider class="mb-4" />
                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputReadonlyWithLabel label="Code" :value="area.code" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputReadonlyWithLabel label="Description" :value="area.description" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputReadonlyWithLabel label="FOR Group" :value="area.group?.description" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
