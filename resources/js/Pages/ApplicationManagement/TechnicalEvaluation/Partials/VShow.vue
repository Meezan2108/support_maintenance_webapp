<script setup>
import ProjectInformation from "./ProjectInformation.vue";
import ProjectEvaluatorShow from "./ProjectEvaluatorShow.vue";
import TableQuestion from "./TableQuestion.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";
import { computed } from "vue";

const props = defineProps({
    proposal: Object,
    evaluation: Object,
    approvalStatus: String,
    questions: Array,
    questionSummary: Array,
    questionProposal: Array,
    questionRisk: Array,
});

const initAnswer = computed(() => {
    let answers = {};

    for (let item of props.questions) {
        let answer = props.evaluation?.answer?.find(
            (ansVal) => ansVal.ref_answer_category_id == item.id
        );

        answers["q_" + item.id] = answer?.answer;
    }

    return answers;
});
</script>

<template>
    <h5 class="mb-3 mt-3">Project's Evaluator Information</h5>
    <ProjectEvaluatorShow
        :user="evaluation.evaluator"
        :value="evaluation.date_evaluation"
    />

    <h5 class="mb-3 mt-3">Project's Information</h5>
    <ProjectInformation :proposal="proposal" />

    <h5 class="mb-3 mt-3">Summary of Assesment</h5>
    <p style="font-style: italic">
        (Please tick appropriate box. Also, provide additional comments in
        Section G. Numbers in parentheses refer to the corresponding section in
        the Application Form)
    </p>
    <TableQuestion
        :questions="questionSummary"
        :value="initAnswer"
        :isView="true"
    />

    <h5 class="mb-3 mt-3">Project Proposal</h5>
    <TableQuestion
        :questions="questionProposal"
        :value="initAnswer"
        :isView="true"
    />

    <h5 class="mb-3 mt-3">Project Risk</h5>
    <TableQuestion
        :questions="questionRisk"
        :value="initAnswer"
        :isView="true"
    />

    <VContentEditorReadonlyWithLabel
        label="General Comments"
        :value="evaluation.comments"
        class="my-4"
    />

    <div class="underline-header mt-2 mb-3">
        <h5>Approval Status</h5>
    </div>
    <div class="row mb-3">
        <div class="col-sm-6">
            <input
                type="text"
                class="form-control"
                :value="approvalStatus"
                readonly
            />
        </div>
    </div>
</template>
