<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import { computed, ref } from "vue";
import { listTabTechEv } from "../tabs.config.js";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VAlert from "@/Shared/VAlert.vue";
import VDevider from "@/Shared/VDevider.vue";
import VTab from "@/Shared/VTab";

import VShow from "./Partials/VShow.vue";

import _ from "lodash";

const props = defineProps({
    title: String,
    additional: Object,
});

const breadcrumbs = [
    {
        url: props.additional.urlIndex,
        label: "Technical Evaluation",
    },
    {
        url: "#",
        label: "View",
    },
];

const elTab = ref(null);

const activeTab = ref(props.additional.initActiveTab ?? "division_director");

const selStep = computed(() => {
    let selected = props.additional.arrStep.find(
        (item) => item.code == activeTab.value
    );

    return selected ?? props.additional.arrStep[0] ?? null;
});

const availabelTab = listTabTechEv.filter((item) => {
    let isStep = props.additional.arrStep.find((step) => {
        return step.code == item.key;
    });

    return isStep?.evaluation != null;
});
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />
        <VAlert />
        <div class="card">
            <div class="card-body">
                <div>
                    <VTab
                        ref="elTab"
                        :listTab="availabelTab"
                        v-model:value="activeTab"
                    />
                </div>
                <h3>Technical Evaluation</h3>
                <VDevider class="my-3" />

                <VShow
                    v-if="selStep"
                    :proposal="additional.proposal"
                    :evaluation="selStep.evaluation"
                    :approvalStatus="selStep.approval_status"
                    :questionSummary="additional.questionSummary"
                    :questionProposal="additional.questionProposal"
                    :questionRisk="additional.questionRisk"
                    :questions="additional.questions"
                />
            </div>
        </div>
    </div>
</template>
