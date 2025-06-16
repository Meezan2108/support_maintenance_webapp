<script setup>
import { computed, ref } from "vue";

import VModalActivities from "../Modals/VModalActivities.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

import { formatMonth } from "@/Helpers/date.js";

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
const initValue = ref(null);
const editedIndex = ref(false);

const emits = defineEmits(["update:value"]);

const activities = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});

const clickAdd = () => {
    initValue.value = null;
    editedIndex.value = false;
    isShowForm.value = true;
};

const clickEdit = (index) => {
    initValue.value = activities.value[index];
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

    activities.value = activities.value.filter((item, key) => index != key);
};

const cancelForm = () => {
    initValue.value = null;
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        activities.value.push({
            id: "",
            activities: value.activities,
            from: value.from,
            to: value.to,
        });
    } else {
        let index = editedIndex.value;
        activities.value[index].activities = value.activities;
        activities.value[index].from = value.from;
        activities.value[index].to = value.to;
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
                        Activities
                        <span v-if="isRequired" class="text-danger">*</span>
                    </td>
                    <td class="fw-bold">From Date</td>
                    <td class="fw-bold">To Date</td>
                </tr>
                <tr v-for="(item, index) in activities" :key="item.id">
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
        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalActivities
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
