<script setup>
import { computed, ref, watch } from "vue";

import RoleRow from "./RoleRow.vue";

const props = defineProps({
    parentIteration: String,
    index: Number,
    menu: Object,
    value: Array,
    isHeader: Boolean,
    isDisabled: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["update:value"]);

const selPermission = computed({
    get() {
        return props.value;
    },
    set(value) {
        emits("update:value", value);
    },
});

const rowNumber = computed(() =>
    props.parentIteration
        ? props.parentIteration + "." + (props.index + 1)
        : props.index + 1
);

const formatPermissionName = (code, permissionName) => {
    return permissionName.replace(code + "-", "");
};
</script>

<template>
    <tr>
        <th>{{ rowNumber }}</th>
        <th v-if="isHeader">{{ menu.name }}</th>
        <td v-else>{{ menu.name }}</td>
        <td>
            <div
                v-for="permission in menu.permission"
                :key="permission.id"
                class="form-check form-check-inline"
            >
                <input
                    class="form-check-input"
                    type="checkbox"
                    :id="'permission' + permission.id"
                    v-model="selPermission"
                    :value="permission.id"
                    :disabled="isDisabled"
                />
                <label
                    class="form-check-label"
                    :for="'permission' + permission.id"
                    >{{
                        formatPermissionName(menu.code, permission.name)
                    }}</label
                >
            </div>
        </td>
    </tr>
    <RoleRow
        v-for="(submenu, index) in menu.children"
        :key="submenu.id"
        :menu="submenu"
        v-model:value="selPermission"
        :parentIteration="rowNumber.toString()"
        :index="index"
        :isHeader="false"
        :isDisabled="isDisabled"
    />
</template>
