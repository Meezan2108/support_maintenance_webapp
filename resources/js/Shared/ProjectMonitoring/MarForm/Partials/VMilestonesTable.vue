<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VMilestonesTableRows from "./VMilestonesTableRows.vue";

const props = defineProps({
    value: {
        type: Array,
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

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
                <VMilestonesTableRows
                    v-for="(item, index) in milestones"
                    :key="item.id"
                    v-model:value="milestones[index]"
                    :index="index"
                />
            </tbody>
        </table>
    </div>
</template>
