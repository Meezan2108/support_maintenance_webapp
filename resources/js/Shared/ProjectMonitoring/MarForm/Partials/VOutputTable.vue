<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalOutput from "../Modals/VModalOutput.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const emits = defineEmits(["update:value"]);

const data = computed({
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
    initValue.value = data.value[index];
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

    data.value = data.value.filter((item, key) => index != key);
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        data.value.push({
            id: "",
            output: value.output,
            date: value.date,
        });
    } else {
        let index = editedIndex.value;
        data.value[index].output = value.output;
        data.value[index].date = value.date;
    }
    isShowForm.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="3">
                        <h6>{{ title }}</h6>
                    </td>
                </tr>
                <tr v-for="(output, index) in data" :key="index">
                    <td class="form-table-action-column text-nowrap">
                        <VButtonIconEdit
                            classStyle="text-warning"
                            @onClick="clickEdit(index)"
                        />
                        <VButtonIconDelete
                            classStyle="text-danger"
                            @onClick="clickDelete(index)"
                        />
                    </td>
                    <td>
                        {{ output.output }}
                    </td>
                    <td>
                        {{ output.date }}
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalOutput
        v-if="isShowForm"
        :title="title"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
