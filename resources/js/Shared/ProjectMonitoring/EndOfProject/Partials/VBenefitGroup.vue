<script setup>
import { reactive } from "vue";
import VBenefitSection from "./VBenefitSection.vue";

const props = defineProps({
    questionGroup: Object,
    answers: Object,
});

const emits = defineEmits(["onChange"]);

let dataAnswers = {};

const onChange = (answers) => {
    for (let [key, value] of Object.entries(answers)) {
        dataAnswers[key] = value;
    }

    emits("onChange", dataAnswers);
};
</script>

<template>
    <div class="mb-4">
        <div class="mb-3">
            <h4 class="m-0">
                {{ questionGroup.order }}.
                {{ questionGroup.title }}
            </h4>
            <small class="fst-italic">{{ questionGroup.description }}</small>
        </div>
        <VBenefitSection
            v-for="section in questionGroup.section"
            :key="section.id"
            :section="section"
            :answers="answers"
            @onChange="onChange"
        />
    </div>
</template>
