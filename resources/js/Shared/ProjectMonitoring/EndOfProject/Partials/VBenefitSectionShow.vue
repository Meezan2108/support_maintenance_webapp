<script setup>
import { convertToRoman, numberToAlphabet } from "@/Helpers/number";
import VMultiShow from "../../../Form/Questions/VMultiShow.vue";
import VMultiTextShow from "../../../Form/Questions/VMultiTextShow.vue";
import VMultiTextValueShow from "../../../Form/Questions/VMultiTextValueShow.vue";
import VMultiTextValue2Show from "../../../Form/Questions/VMultiTextValue2Show.vue";
import VMultiValueShow from "../../../Form/Questions/VMultiValueShow.vue";
import VTableShow from "../../../Form/Questions/VTableShow.vue";

const props = defineProps({
    section: Object,
    answers: Object,
});

const emits = defineEmits(["onChange"]);

const makeLabel = (number, title) => {
    return numberToAlphabet(number).toLowerCase() + ". " + title;
};
</script>

<template>
    <div class="mb-3 ms-2">
        <h5 class="mb-2">
            {{ convertToRoman(section.order).toLowerCase() }}.
            {{ section.title }}
        </h5>

        <div
            v-for="(question, index) in section.question"
            :key="question.id"
            class="mb-3 ms-2"
        >
            <VMultiShow
                v-if="question.type == 'multi'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                :value="answers['q_' + question.id]"
                :error="''"
            />

            <VMultiTextShow
                v-if="question.type == 'multitext'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                otherOptionLabel="Other, please specify:"
                :value="answers['q_' + question.id]"
                :error="''"
            />

            <VMultiTextValueShow
                v-if="question.type == 'multitextvalue'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                otherOptionLabel="Other, please specify:"
                :value="answers['q_' + question.id]"
                :error="''"
            />

            <VMultiTextValue2Show
                v-if="question.type == 'multitextvalue2'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                otherOptionLabel="Other, please specify:"
                :value="answers['q_' + question.id]"
                :error="''"
            />

            <VMultiValueShow
                v-if="question.type == 'multivalue'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                :value="answers['q_' + question.id]"
                :error="''"
            />

            <VTableShow
                v-if="question.type == 'table'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                :value="answers['q_' + question.id]"
                :error="''"
            />
        </div>
    </div>
</template>
