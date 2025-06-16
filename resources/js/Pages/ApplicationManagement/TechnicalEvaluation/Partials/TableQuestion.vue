<script setup>
import { computed } from "vue";
import TableQuestionRow from "./TableQuestionRow.vue";

const props = defineProps({
    questions: Array,
    value: Object,
    isView: {
        type: Boolean,
        default: false,
    },
});

const answerOptions = props.questions[0].options;

const emits = defineEmits(["update:value"]);

const selAnswer = computed({
    get() {
        return props.value;
    },
    set(newValue) {
        emits("update:value", newValue);
    },
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td class="fw-bold"></td>
                    <td
                        v-for="item in answerOptions"
                        :key="item"
                        width="15%"
                        class="text-center"
                    >
                        {{ item }}
                    </td>
                </tr>
                <tr v-for="item in questions" :key="item.id">
                    <TableQuestionRow
                        :question="item"
                        v-model:value="value['q_' + item.id]"
                        :answerOptions="answerOptions"
                        :isView="isView"
                    />
                </tr>
            </tbody>
        </table>
    </div>
</template>
