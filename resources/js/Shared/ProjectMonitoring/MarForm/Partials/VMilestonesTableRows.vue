<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
    value: {
        type: Array,
    },
    index: Number,
});

const emits = defineEmits(["update:value"]);

const milestone = ref({
    id: props.value?.id,
    proposal_id: props.value?.proposal_id,
    activities: props.value?.activities,
    from: props.value?.from,
    is_achieved: props.value?.is_achieved,
    completion_date: props.value?.completion_date,
});

watch(
    () => milestone.value.is_achieved,
    (newValue) => {
        emits("update:value", milestone.value);
    }
);

watch(
    () => milestone.value.completion_date,
    (newValue) => {
        emits("update:value", milestone.value);
    }
);
</script>

<template>
    <tr>
        <td class="text-nowrap fw-bold">
            {{ index + 1 }}
        </td>
        <td>{{ milestone.activities }}</td>
        <td>{{ milestone.from }}</td>
        <td>
            <select class="form-select" v-model="milestone.is_achieved">
                <option value="1">YES</option>
                <option value="0">NO</option>
            </select>
        </td>
        <td>
            <input
                type="month"
                class="form-control"
                v-model="milestone.completion_date"
            />
        </td>
    </tr>
</template>
