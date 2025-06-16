<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalResearcherInvolved from "./VModalResearcherInvolved.vue";
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
    const searchIndex = projectTeams.value.findIndex(
        (item) => item.user_id == value.user_id
    );

    /**
     * 1 ditemukan dan posisi bukan edit atau ditemukan tapi edit index == search index)
     */
    if (
        (searchIndex != -1 && editedIndex.value === false) ||
        (searchIndex != -1 && editedIndex.value != searchIndex)
    ) {
        Swal.fire({
            icon: "warning",
            title: "Data Sudah ada!",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Oke!",
        });

        isShowForm.value = false;

        return false;
    }

    if (editedIndex.value === false) {
        projectTeams.value.push({
            id: "",
            user_id: value.user_id,
            name: value.name,
            type: value.type,
        });
    } else {
        let index = editedIndex.value;
        projectTeams.value[index].user_id = value.user_id;
        projectTeams.value[index].name = value.name;
        projectTeams.value[index].type = value.type;
    }
    isShowForm.value = false;

    emits("update:value", projectTeams.value);
};
</script>

<template>
    <div class="bg-light p-2 mb-3">
        <table class="table table-borderless">
            <caption class="d-none">
                List Researcher
            </caption>
            <tbody>
                <tr>
                    <th
                        style="width: 100px"
                        class="form-table-action-column"
                    ></th>
                    <th class="fw-bold">
                        {{ title }}
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
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
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalResearcherInvolved
        v-if="isShowForm"
        :title="title"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
