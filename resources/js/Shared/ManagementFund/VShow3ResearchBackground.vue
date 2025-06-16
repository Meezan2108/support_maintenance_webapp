<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>
<template>
    <h3>Research Background</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Location of Research"
                :value="initValue.research_location"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Project Summary"
                :value="initValue.project_summary"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Problem Statement"
                :value="initValue.problem_statement"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Hypothesis"
                :value="initValue.hypothesis"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Research Question"
                :value="initValue.research_question"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Literature Review"
                :value="initValue.literature_review"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Relevance of Goverment Policy"
                :value="initValue.relevance_goverment_policy"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Reference"
                :value="initValue.reference"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                label="Related Research"
                :value="initValue.related_research"
            />
        </div>
    </div>
</template>
