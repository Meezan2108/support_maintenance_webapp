<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalMilestones from "../Modals/VModalMilestones.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
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

const milestones = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});

const clickAdd = () => {
    initValue.value = {};
    editedIndex.value = false;
    isShowForm.value = true;
};

const clickEdit = (index) => {
    initValue.value = milestones.value[index];
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

    milestones.value = milestones.value.filter((item, key) => index != key);
};

const cancelForm = () => {
    initValue.value = "";
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        milestones.value.push({
            id: "",
            activities: value.activities,
            from: value.from,
        });
    } else {
        let index = editedIndex.value;
        milestones.value[index].activities = value.activities;
        milestones.value[index].from = value.from;
    }
    isShowForm.value = false;
};
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
                <tr v-for="(item, index) in milestones" :key="item.id">
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
                    <td>{{ item.activities }}</td>
                    <td>{{ item.from }}</td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalMilestones
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
