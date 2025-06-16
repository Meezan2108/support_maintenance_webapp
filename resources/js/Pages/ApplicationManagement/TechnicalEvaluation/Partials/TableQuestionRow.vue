<script setup>
import { computed } from "vue";

const props = defineProps({
    question: Object,
    value: Object,
    answerOptions: Array,
    isView: Boolean,
});

const emits = defineEmits(["update:value"]);
</script>

<template>
    <td>{{ question.description }}</td>
    <td
        v-for="itemOption in answerOptions"
        :key="itemOption"
        class="text-center"
    >
        <input
            class="form-check-input"
            type="checkbox"
            :id="'question' + question.id"
            @input="
                $emit(
                    'update:value',
                    value == $event.target.value ? null : $event.target.value
                )
            "
            :value="itemOption"
            :checked="itemOption == value"
            :disabled="isView"
        />
    </td>
</template>
