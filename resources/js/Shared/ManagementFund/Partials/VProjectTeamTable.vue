<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalProjectTeam from "../Modals/VModalProjectTeam.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    userType: String,
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const emits = defineEmits(["update:value"]);

const projectTeams = computed({
    get() {
        return props.value;
    },
});

const clickAdd = () => {
    initValue.value = {};
    editedIndex.value = false;
    isShowForm.value = true;
};

const clickEdit = (index) => {
    initValue.value = projectTeams.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const clickDelete = async (index) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Are you sure?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    emits(
        "update:value",
        projectTeams.value.filter((item, key) => index != key)
    );
};

const cancelForm = () => {
    initValue.value = "";
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        projectTeams.value.push({
            id: "",
            user_id: value.user_id,
            name: value.name,
            organization: value.organization,
            man_month: value.man_month,
        });
    } else {
        let index = editedIndex.value;
        projectTeams.value[index].name = value.name;
        projectTeams.value[index].user_id = value.user_id;
        projectTeams.value[index].organization = value.organization;
        projectTeams.value[index].man_month = value.man_month;
    }
    isShowForm.value = false;

    emits("update:value", projectTeams.value);
};
</script>

<template>
    <div class="bg-light p-2 mb-3">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th width="45%" class="fw-bold">
                        {{ title }}
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th width="35%" class="fw-bold">Organization</th>
                    <th width="15%" class="fw-bold">Man - Month</th>
                </tr>
                <tr v-for="(item, index) in projectTeams" :key="item.id">
                    <td class="text-nowrap">
                        <VButtonIconEdit
                            classStyle="text-warning"
                            @onClick="clickEdit(index)"
                        />
                        <VButtonIconDelete
                            classStyle="text-danger"
                            @onClick="clickDelete(index)"
                        />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.organization }}</td>
                    <td>{{ item.man_month }}</td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalProjectTeam
        v-if="isShowForm"
        :title="title"
        :value="initValue"
        :type="userType"
        :isRequired="isRequired"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
