<script setup>
import { computed } from "vue";

import _ from "lodash";

const props = defineProps({
    value: {
        type: Array,
    },
    readonlyValue: {
        type: Array,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["update:value"]);

const milestones = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <td class="fw-bold">
                        Milestone
                        <span v-if="isRequired" class="text-danger">*</span>
                    </td>
                    <td class="fw-bold">Date</td>
                </tr>
                <tr v-for="(item, index) in readonlyValue" :key="item.id">
                    <td>-</td>
                    <td>{{ item.activities }}</td>
                    <td>{{ item.from }}</td>
                </tr>
                <tr v-for="(item, index) in milestones" :key="item.id">
                    <td>-</td>
                    <td>{{ item.activities }}</td>
                    <td>{{ item.from }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
