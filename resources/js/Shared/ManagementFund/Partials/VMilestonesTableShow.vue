<script setup>
import { ref } from "vue";

import _ from "lodash";
import VModalMilestonesShow from "../Modals/VModalMilestonesShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";


const props = defineProps({
    value: {
        type: Array,
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const clickShow = (index) => {
    initValue.value = props.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = "";
    isShowForm.value = false;
    editedIndex.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <td class="fw-bold">Milestone</td>
                    <td class="fw-bold">Date</td>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td class="text-nowrap">
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.activities }}</td>
                    <td>{{ item.from }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalMilestonesShow
        v-if="isShowForm"
        :value="initValue"
        @onCancel="cancelForm"
    />
</template>
