<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VAlert from "@/Shared/VAlert.vue";
import VShow from "./_partials/VShow.vue";
import VTextareaComment from "@/Shared/Form/VTextareaComment.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import { generateArrYear } from "@/Helpers/date.js";

import { computed, onMounted, ref } from "vue";
import Swal from "sweetalert2";
import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    title: String,
    additional: Object,
});

const {
    urlIndex,
    urlSubmit,
    initValue,
    approvement,
    myApprovement,
    optionsStatus,
    otherMilestones,
} = props.additional;

const { proposal } = initValue;
const granttchart = ref([]);

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Extension of Project",
    },
    {
        url: "#",
        label: "Comment/Approval Application",
    },
];

const form = useForm({
    comment: myApprovement?.comments?.comment,
    status: myApprovement?.status,
    is_submited: 0,
    _method: "PUT",
});

onMounted(() => {
    if (initValue?.granttchart) {
        granttchart.value = initValue?.granttchart.map((item) => ({
            id: item.id,
            activities: item.description,
            from: item.from.substr(0, 10),
            to: item.from.substr(0, 10),
        }));
    }
});

const submitComment = async (comment) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Approval!",
    });

    form.is_submited = 0;
    if (result.isConfirmed) {
        form.is_submited = 1;
    }

    form.comment = comment;

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
        },
    });
};
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <VSelectDefaultWithLabel
                                elId="status"
                                label="Approval Status"
                                v-model:value="form.status"
                                :options="optionsStatus"
                                :error="form.errors.status"
                            />
                        </div>
                    </div>

                    <VTextareaComment
                        @onSubmit="submitComment"
                        :isProcessing="form.processing"
                        :value="form.comment"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
