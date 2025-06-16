<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { useForm } from "@inertiajs/vue3";
import { computed, watch } from "vue";

import { useFormStore } from "@/Store/form.js";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, proposalType } = props.additional;

const identification = computed(() => props.additional.identification);

const form = useForm({
    project_leader_type: identification.value?.project_leader_type,
    proposal_type: proposalType,
    research_location: initValue?.research_location ?? "",
    project_summary: initValue?.project_summary ?? "",
    problem_statement: initValue?.problem_statement ?? "",
    hypothesis: initValue?.hypothesis ?? "",
    research_question: initValue?.research_question ?? "",
    literature_review: initValue?.literature_review ?? "",
    relevance_goverment_policy: initValue?.relevance_goverment_policy ?? "",
    reference: initValue?.reference ?? "",
    related_research: initValue?.related_research ?? "",
    _method: method,
});

const formStore = useFormStore();

const emits = defineEmits(["onNext", "onPrev"]);

const isLeaderInternal = computed(() => {
    return identification.value?.project_leader_type == 1;
});

const handleClickNext = () => {
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
            emits("onNext", form.data());
        },
    });
};

const handleClickPrev = () => {
    emits("onPrev", form);
};

watch(
    () => JSON.stringify(form.data()),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Research Background</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="research_location"
                label="Location of Research"
                v-model:value="form.research_location"
                :error="form.errors.research_location"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="project_summary"
                label="Project Summary"
                v-model:value="form.project_summary"
                :error="form.errors.project_summary"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="problem_statement"
                label="Problem Statement"
                v-model:value="form.problem_statement"
                :error="form.errors.problem_statement"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="hypothesis"
                label="Hypothesis"
                v-model:value="form.hypothesis"
                :error="form.errors.hypothesis"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="research_question"
                label="Research Question"
                v-model:value="form.research_question"
                :error="form.errors.research_question"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="literature_review"
                label="Literature Review"
                v-model:value="form.literature_review"
                :error="form.errors.literature_review"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="relevance_goverment_policy"
                label="Relevance of Goverment Policy"
                v-model:value="form.relevance_goverment_policy"
                :error="form.errors.relevance_goverment_policy"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="reference"
                label="Reference"
                v-model:value="form.reference"
                :error="form.errors.reference"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="related_research"
                label="Related Research"
                v-model:value="form.related_research"
                :error="form.errors.related_research"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButton class="me-2" type="button" @onClick="handleClickPrev">
            Back
        </VButton>
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>
