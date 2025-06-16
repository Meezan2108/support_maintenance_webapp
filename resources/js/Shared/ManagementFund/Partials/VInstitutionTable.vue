<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalInstitution from "../Modals/VModalInstitution.vue";
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

const organizations = computed({
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
    initValue.value = organizations.value[index];
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
        organizations.value.filter((item, key) => index != key)
    );
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        organizations.value.push({
            id: "",
            name: value.name,
            other: value.other,
            role: value.role,
        });
    } else {
        let index = editedIndex.value;
        organizations.value[index].name = value.name;
        organizations.value[index].other = value.other;
        organizations.value[index].role = value.role;
    }
    isShowForm.value = false;

    emits("update:value", organizations.value);
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="form-table-action-column"></th>
                    <th class="fw-bold">
                        Organizations Involved
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th class="fw-bold">Other</th>
                    <th class="fw-bold">Role</th>
                </tr>
                <tr v-for="(item, index) in organizations" :key="item.id">
                    <td>
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
                    <td>{{ item.other }}</td>
                    <td>{{ item.role }}</td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalInstitution
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
