<script setup>
import VMulti from "@/Shared/Form/Questions/VMulti.vue";
import VMultiText from "@/Shared/Form/Questions/VMultiText.vue";
import VMultiTextValue from "@/Shared/Form/Questions/VMultiTextValue.vue";
import VMultiValue from "@/Shared/Form/Questions/VMultiValue.vue";
import VMultiValueWithVisibilityStatus from "@/Shared/Form/Questions/VMultiValueWithVisibilityStatus.vue";

import { convertToRoman, numberToAlphabet } from "@/Helpers/number";
import VMultiTextValue2 from "@/Shared/Form/Questions/VMultiTextValue2.vue";
import VTable from "@/Shared/Form/Questions/VTable.vue";
import { reactive } from "vue";

const props = defineProps({
    section: Object,
    answers: Object,
});

const emits = defineEmits(["onChange"]);

const form = reactive({
    answers: {},
});

const makeLabel = (number, title) => {
    return numberToAlphabet(number).toLowerCase() + ". " + title;
};

const initReactiveData = (questions) => {
    let questionId = "";
    for (const item of questions) {
        questionId = "q_" + item.id;
        form.answers[questionId] = props.answers[questionId] ?? [];
    }
};

const onChange = () => {
    emits("onChange", form.answers);
};

initReactiveData(props.section.question);
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
            <VMulti
                v-if="question.type == 'multi'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VMultiText
                v-if="question.type == 'multitext'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                otherOptionLabel="Other, please specify:"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VMultiTextValue
                v-if="question.type == 'multitextvalue'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                otherOptionLabel="Other, please specify:"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VMultiTextValue2
                v-if="question.type == 'multitextvalue2'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                otherOptionLabel="Other, please specify:"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VMultiValue
                v-if="question.type == 'multivalue'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :optionValueLabel="question.options.label"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VMultiValueWithVisibilityStatus
                v-if="question.type == 'multivalue_with_visibility_status'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options.options"
                :visibilityStatus="question.options.visibility_value"
                :optionValueLabel="question.options.label"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />

            <VTable
                v-if="question.type == 'table'"
                :elId="'q_' + question.id"
                :label="makeLabel(index + 1, question.content)"
                :options="question.options"
                v-model:value="form.answers['q_' + question.id]"
                @onChange="onChange"
                :error="''"
            />
        </div>
    </div>
</template>
