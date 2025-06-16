<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalIndustries from "../Modals/VModalIndustries.vue";
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

const industries = computed({
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
    initValue.value = industries.value[index];
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
        industries.value.filter((item, key) => index != key)
    );
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        industries.value.push({
            id: "",
            name: value.name,
            role: value.role,
        });
    } else {
        let index = editedIndex.value;
        industries.value[index].name = value.name;
        industries.value[index].role = value.role;
    }
    isShowForm.value = false;

    emits("update:value", industries.value);
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th class="fw-bold">
                        Industry
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th class="fw-bold">Role</th>
                </tr>
                <tr v-for="(item, index) in industries" :key="item.id">
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
                    <td>{{ item.role }}</td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalIndustries
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
