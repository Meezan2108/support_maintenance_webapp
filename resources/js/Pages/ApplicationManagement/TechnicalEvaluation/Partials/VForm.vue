<script setup>
import ProjectInformation from "./ProjectInformation.vue";
import ProjectEvaluatorShow from "./ProjectEvaluatorShow.vue";
import TableQuestion from "./TableQuestion.vue";

import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    proposal: Object,
    dateNow: String,
    evaluator: Object,
    evaluation: Object,
    approvalStatus: String,
    questions: Array,
    questionSummary: Array,
    questionProposal: Array,
    questionRisk: Array,
    optionsStatus: Array,
    urlUpdate: String,
});

var initAnswer = {};
for (let item of props.questions) {
    let answer = props.evaluation?.answer.find(
        (ansVal) => ansVal.ref_answer_category_id == item.id
    );

    initAnswer["q_" + item.id] = answer?.answer;
}

const form = useForm({
    evaluator_id: props.evaluation?.evaluator_id ?? props.evaluator.id,
    date_evaluation: props.evaluation
        ? props.evaluation.date_evaluation
        : props.dateNow,
    comments: props.evaluation?.comments,
    approval_status: props.evaluation?.approval_status,
    answers: initAnswer,
    _method: "PUT",
});

const optionsStatus = props.optionsStatus;

const handleClickSubmit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Evaluation!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    form.post(props.urlUpdate, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
        },
    });
};
</script>

<template>
    <h5 class="mb-3 mt-3">Project's Evaluator Information</h5>
    <ProjectEvaluatorShow :user="evaluator" :value="form.date_evaluation" />

    <h5 class="mb-3 mt-3">Project's Information</h5>
    <ProjectInformation :proposal="proposal" />

    <h5 class="mb-3 mt-3">Summary of Assesment</h5>
    <p style="font-style: italic">
        (Please tick appropriate box. Also, provide additional comments in
        Section G. Numbers in parentheses refer to the corresponding section in
        the Application Form)
    </p>
    <TableQuestion :questions="questionSummary" v-model:value="form.answers" />

    <h5 class="mb-3 mt-3">Project Proposal</h5>
    <TableQuestion :questions="questionProposal" v-model:value="form.answers" />

    <h5 class="mb-3 mt-3">Project Risk</h5>
    <TableQuestion :questions="questionRisk" v-model:value="form.answers" />

    <div class="my-3">
        <VContentEditorWithLabel
            label="General Comments"
            v-model:value="form.comments"
        />
    </div>

    <div class="underline-header mt-2 mb-3">
        <h5>
            Approval Status
            <span class="text-danger">*</span>
        </h5>
    </div>
    <div class="row mb-3">
        <div class="col-sm-6">
            <select
                id="approval_status"
                class="form-select"
                :class="{
                    'is-invalid': form.errors?.approval_status,
                }"
                v-model="form.approval_status"
            >
                <option
                    v-for="option in optionsStatus"
                    :key="option.id"
                    :value="option.id"
                    :selected="option.id == form.approval_status"
                >
                    {{ option.description }}
                </option>
            </select>
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickSubmit"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>
