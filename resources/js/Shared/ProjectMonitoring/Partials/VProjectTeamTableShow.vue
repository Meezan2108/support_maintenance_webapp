<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalProjectTeamShow from "./VModalProjectTeamShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";
import { LIST_TEAM_TYPE } from "@/Config/projectTeam";

const props = defineProps({
    title: String,
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

const formatTeamType = (typeId) => {
    const type = LIST_TEAM_TYPE.find((item) => item.id == typeId);
    return type?.description ?? " - ";
};
</script>

<template>
    <div class="bg-light p-2 mb-3">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th width="45%" class="fw-bold">{{ title }}</th>
                    <th width="35%" class="fw-bold">Type</th>
                    <th width="35%" class="fw-bold">Organization</th>
                    <th width="15%" class="fw-bold">Man - Month</th>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td class="text-nowrap">
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ formatTeamType(item.type) }}</td>
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
