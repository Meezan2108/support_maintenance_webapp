<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalCommercializaition from "../Modals/VModalCommercializaition.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
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
    initValue.value = null;
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    if (editedIndex.value === false) {
        data.value.push({
            id: "",
            name: value.name,
            taker: value.taker,
            category: value.category,
            category_description: value.category_description,
            date: value.date,
        });
    } else {
        let index = editedIndex.value;
        data.value[index].name = value.name;
        data.value[index].taker = value.taker;
        data.value[index].category = value.category;
        data.value[index].category_description = value.category_description;
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
                    <td colspan="5">
                        <h6>Commercialisation</h6>
                    </td>
                </tr>
                <tr v-for="(ipr, index) in data" :key="index">
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
                        {{ ipr.name }}
                    </td>
                    <td>
                        {{ ipr.taker }}
                    </td>
                    <td>
                        {{ ipr.category_description }}
                    </td>
                    <td>
                        {{ ipr.date }}
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-default" @click="clickAdd">
            <span class="material-icons me-1">add</span>
            Add row
        </button>
    </div>
    <VModalCommercializaition
        v-if="isShowForm"
        :value="initValue"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
