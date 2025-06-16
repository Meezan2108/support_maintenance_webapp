<script setup>
import { computed, ref } from "vue";

import VModalActivitiesShow from "../Modals/VModalActivitiesShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

import { formatMonth } from "@/Helpers/date.js";

const props = defineProps({
    value: {
        type: Array,
    },
});

const isShowForm = ref(false);
const initValue = ref(null);
const editedIndex = ref(false);

const clickShow = (index) => {
    initValue.value = props.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = null;
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
                    <td class="fw-bold">Activities</td>
                    <td class="fw-bold">From Date</td>
                    <td class="fw-bold">To Date</td>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td class="text-nowrap">
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.activities }}</td>
                    <td>
                        {{
                            formatMonth(item.from ? item.from.substr(0, 7) : "")
                        }}
                    </td>
                    <td>
                        {{ formatMonth(item.to ? item.to.substr(0, 7) : "") }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalActivitiesShow
        v-if="isShowForm"
        :value="initValue"
        @onCancel="cancelForm"
    />
</template>
