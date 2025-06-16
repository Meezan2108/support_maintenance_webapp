<script setup>
import { Head } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";

import VTextareaCommentShow from "@/Shared/Form/VTextareaCommentShow.vue";
import VShow from "./_partials/VShow.vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, initValue, otherMilestones, approvement } = props.additional;

const { proposal } = initValue;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Extension of Project",
    },
    {
        url: "#",
        label: "Show Application",
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
                <h3>Extension of Project Application</h3>
                <VDevider class="my-3" />

                <VShow
                    :proposal="proposal"
                    :initValue="initValue"
                    :otherMilestones="otherMilestones"
                />

                <div id="comments">
                    <div class="underline-header mt-2 mb-3">
                        <h5>Comments</h5>
                    </div>

                    <div class="mb-3">
                        <VTextareaCommentShow
                            :value="approvement"
                            activeTab="comment"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
