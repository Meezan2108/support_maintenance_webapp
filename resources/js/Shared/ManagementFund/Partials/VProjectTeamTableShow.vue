<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalProjectTeamShow from "../Modals/VModalProjectTeamShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const clickAdd = () => {
    initValue.value = {};
    editedIndex.value = false;
    isShowForm.value = true;
};

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
    <div class="bg-light p-2 mb-3">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th width="45%" class="fw-bold">{{ title }}</th>
                    <th width="35%" class="fw-bold">Organization</th>
                    <th width="15%" class="fw-bold">Man - Month</th>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td class="text-nowrap">
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.organization }}</td>
                    <td>{{ item.man_month }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalProjectTeamShow
        v-if="isShowForm"
        :title="title"
        :value="initValue"
        @onCancel="cancelForm"
    />
</template>
