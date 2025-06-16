<script setup>
import VBenefitSectionShow from "./VBenefitSectionShow.vue";

const props = defineProps({
    questionGroup: Object,
    answers: Object,
});
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
        <VBenefitSectionShow
            v-for="section in questionGroup.section"
            :key="section.id"
            :section="section"
            :answers="answers"
        />
    </div>
</template>
