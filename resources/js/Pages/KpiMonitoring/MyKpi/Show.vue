<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import Asl from "./_partials/Asl.vue";
import Commercialization from "./_partials/Commercialization.vue";
import ImportedGermplasm from "./_partials/ImportedGermplasm.vue";
import Ipr from "./_partials/Ipr.vue";
import OutputRnd from "./_partials/OutputRnd.vue";
import Publication from "./_partials/Publication.vue";
import Recognition from "./_partials/Recognition.vue";

import _ from "lodash";
import { computed } from "vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, type, filters } = props.additional;

let initValue = props.additional.initValue;
const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "My KPI Achievement",
    },
    {
        url: "#",
        label: props.additional.title,
    },
];

initValue.kpi_achievement.researcher =
    initValue.kpi_achievement?.researcher?.map((item) => {
        return {
            user_id: item.id,
            name: item.pivot.name,
            type: item.pivot.type,
        };
    });
const activeComponent = computed({
    get() {
        switch (type) {
            case "publication":
                return {
                    component: Publication,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "recognition":
                return {
                    component: Recognition,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "ipr":
                return {
                    component: Ipr,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "output_rnd":
                return {
                    component: OutputRnd,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "commercialization":
                return {
                    component: Commercialization,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "asl":
                return {
                    component: Asl,
                    additional: {
                        initValue: initValue,
                    },
                };
            case "imported_germplasm":
                return {
                    component: ImportedGermplasm,
                    additional: {
                        initValue: initValue,
                    },
                };
            default:
                return null;
        }
    },
});
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
                        {{ additional.title }}
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <component
                    :is="activeComponent.component"
                    :additional="activeComponent.additional"
                />
            </div>
        </div>
    </div>
</template>
