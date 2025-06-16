<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VAlert from "@/Shared/VAlert.vue";

import VApprovedProposalForm from "./_partials/VApprovedProposalForm.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const strType =
    props.additional.proposalType == 1 ? "( TRF )" : "( External Fund )";

const breadcrumbs = [
    {
        url: props.additional.urlIndex,
        label: "List of Approved Project",
    },
    {
        url: "#",
        label: "Create Approved Proposal " + strType,
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
                <VAlert />
                <VApprovedProposalForm
                    :initValue="additional.initValue"
                    :initActiveTab="additional.activeTab"
                    :urlBase="additional.urlBase"
                    :urlSubmit="additional.urlStore"
                    :refBenefits="additional.refBenefits"
                    :refProjectCostSeriesDirect="
                        additional.refProjectCostSeriesDirect
                    "
                    :proposalType="additional.proposalType"
                    type="create"
                    method="POST"
                />
            </div>
        </div>
    </div>
</template>
