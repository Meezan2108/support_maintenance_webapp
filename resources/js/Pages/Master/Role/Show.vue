<script setup>
import { ref, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VDevider from "@/Shared/VDevider.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VHeaderButtonEdit from "@/Shared/HeaderButton/VButtonEdit.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import RoleRow from "./_partials/RoleRow.vue";

const props = defineProps({
    filters: Array,
    role: Object,
    menulist: Array,
    canEdit: Boolean,
    urlIndex: String,
    urlEdit: String,
});

const activePermission = props.role.permissions.map((item) => item.id);

const breadcrumbs = [
    {
        url: props.urlIndex,
        label: "Role Management",
    },
    {
        url: "#",
        label: "Detail Role",
    },
];

const form = useForm({
    selPermission: activePermission,
});

const formatPermissionName = (code, permissionName) => {
    return permissionName.replace(code + "-", "");
};
</script>

<template>
    <Head>
        <title>Show - Role Management</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Detail Role
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonEdit v-if="canEdit" :href="urlEdit" />
                    </div>
                </div>

                <VDevider />
                <div class="row">
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Name"
                            type="text"
                            :value="role.name"
                        />
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Menu</th>
                                <th>Permission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <RoleRow
                                v-for="(menu, index) in menulist"
                                :key="menu.id"
                                :menu="menu"
                                v-model:value="form.selPermission"
                                parentIteration=""
                                :index="index"
                                :isHeader="true"
                                :isDisabled="true"
                            />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
