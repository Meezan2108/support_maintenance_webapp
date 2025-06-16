<script setup>
import { computed, ref } from "vue";

import { clearHtmlString } from "@/Helpers/string.js";
import _ from "lodash";
import VModalObjectives from "../Modals/VModalObjectives.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
    label: String,
    subLabel: String,
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

const objectives = computed({
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
    initValue.value = objectives.value[index];
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

    console.log(objectives.value);
    objectives.value = objectives.value.filter((item, key) => index != key);
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        objectives.value.push({
            id: "",
            description: value.description,
        });
    } else {
        let index = editedIndex.value;
        objectives.value[index].description = value.description;
    }
    isShowForm.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2">
                        <h6>
                            {{ label }}
                            <span v-if="isRequired" class="text-danger">*</span>
                        </h6>
                        <div v-if="subLabel">
                            <small class="fst-italic">{{ subLabel }}</small>
                        </div>
                    </td>
                </tr>
                <tr
                    v-for="(objective, index) in objectives"
                    :key="objective.id"
                >
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
                        {{
                            _.truncate(clearHtmlString(objective.description), {
                                length: 50,
                                separator: /,? +/,
                            })
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalObjectives
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
