<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
    value: {
        type: Array,
    },
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <td class="fw-bold" width="45%">Planned Milestone</td>
                    <td class="fw-bold text-center" width="20%">
                        Planned Milestone Date
                    </td>
                    <td class="fw-bold text-center" with="15%">
                        Achieved<br />(YES/NO)
                    </td>
                    <td class="fw-bold text-center" width="20%">
                        Actual Completion Date<br />(Year-Month)
                    </td>
                </tr>
                <tr v-for="(item, index) in value">
                    <td class="text-nowrap fw-bold text-center">
                        {{ index + 1 }}
                    </td>
                    <td>{{ item.activities }}</td>
                    <td class="text-center">{{ item.from }}</td>
                    <td class="text-center">
                        {{ item.is_achieved ? "Yes" : "No" }}
                    </td>
                    <td class="text-center">{{ item.completion_date }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
