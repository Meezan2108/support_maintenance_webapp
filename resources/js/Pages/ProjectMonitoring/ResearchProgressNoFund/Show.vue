<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";
import VShow from "./_partials/VShow.vue";

import _ from "lodash";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, approvement } = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Research Progress Report (No Fund)",
    },
    {
        url: "#",
        label: "Show Report",
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
                <h3>Research Project Report</h3>
                <VDevider class="my-3" />

                <VShow :initValue="initValue" />

                <VDevider class="mb-4" />

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Comments</h5>
                    </div>

                    <div class="mb-3">
                        <VTextareaCommentShow :value="approvement" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
