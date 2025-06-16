<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import VDevider from "@/Shared/VDevider.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VSelectMultipleWithLabel from "@/Shared/Form/VSelectMultipleWithLabel.vue";
import VInputPasswordWithLabel from "@/Shared/Form/VInputPasswordWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VUploadPorfilePictureWithLabel from "@/Shared/Form/VUploadPorfilePictureWithLabel.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VAlert from "@/Shared/VAlert.vue";

const props = defineProps({
    divisions: Array,
    positions: Array,
    roles: Array,
    filters: {
        type: Object,
        default: null,
    },
    urlStore: String,
    urlIndex: String,
});

const form = useForm({
    file_picture: null,
    staf_id: "",
    name: "",
    salutation: "",
    working_address: "",
    qualification: "",
    ref_division_id: "",
    ref_position_id: "",
    roles: [],
    status: 1,
    email: "",
    password: "",
    password_confirmation: "",
});

// const positions = [
//     { id: 1, description: "Manager" },
//     { id: 2, description: "Assistant Manager" },
//     { id: 3, description: "Project Director" },
//     { id: 4, description: "Senior Data Analyst" },
//     { id: 5, description: "Programmer & Database Administrator" },
//     { id: 6, description: "Database Administrator" },
//     { id: 7, description: "Internship" },
//     { id: 8, description: "Project Manager" },
// ];


const arrStatus = [
    { id: 1, description: "Active" },
    { id: 0, description: "Non-Active" },
];

const breadcrumbs = [
    {
        url: "/user",
        label: "User Management",
    },
    {
        url: "#",
        label: "Add New User",
    },
];

const submit = () => {
    form.post(props.urlStore, {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head>
        <title>Add New User - User Management</title>
    </Head>
    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Add New User
                    </VTitleWithBackLink>
                </div>
                <VDevider />

                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-lg-6 mt-4 mb-3">
                            <VUploadPorfilePictureWithLabel
                                elId="picture"
                                label="Upload Picture"
                                initPicture=""
                                v-model:value="form.file_picture"
                                :error="form.errors.file_picture"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="staf_id"
                                label="Staff ID"
                                type="text"
                                :error="form.errors.staf_id"
                                v-model:value="form.staf_id"
                            />
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="salutation"
                                label="Salutation"
                                type="text"
                                :error="form.errors.salutation"
                                v-model:value="form.salutation"
                            />
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="name"
                                label="Name"
                                type="text"
                                :error="form.errors.name"
                                v-model:value="form.name"
                            />
                        </div>
                        <!-- <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="qualification"
                                label="Qualification"
                                type="text"
                                :error="form.errors.qualification"
                                v-model:value="form.qualification"
                            />
                        </div> -->

                        <!-- <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="working_address"
                                label="Working Address"
                                :error="form.errors.working_address"
                                v-model:value="form.working_address"
                            />
                        </div>
                    </div> -->
                    <!-- <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VSelectWithLabel
                                elId="division"
                                label="Division"
                                :error="form.errors.ref_division_id"
                                v-model:value="form.ref_division_id"
                                :options="divisions"
                            />
                        </div> -->
                        <div class="col-lg-6 mb-3">
                            <VSelectWithLabel
                                elId="position"
                                label="Position"
                                :error="form.errors.ref_position_id"
                                v-model:value="form.ref_position_id"
                                :options="positions"
                            />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <VSelectMultipleWithLabel
                                elId="role"
                                label="Role"
                                :error="form.errors.roles"
                                v-model:value="form.roles"
                                :options="roles"
                            />
                        </div>
                        <div class="col-lg-6">
                            <VSelectDefaultWithLabel
                                elId="status"
                                label="Status"
                                :error="form.errors.status"
                                v-model:value="form.status"
                                :options="arrStatus"
                            />
                        </div>
                    </div>
                    <VDevider class="my-4" />

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="email"
                                label="Email"
                                type="email"
                                :error="form.errors.email"
                                v-model:value="form.email"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputPasswordWithLabel
                                elId="password"
                                label="New Password"
                                :error="form.errors.password"
                                v-model:value="form.password"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputPasswordWithLabel
                                elId="password_confirmation"
                                label="New Password Confirmation"
                                :error="form.errors.password_confirmation"
                                v-model:value="form.password_confirmation"
                            />
                        </div>
                    </div>
                    <VDevider class="my-4" />

                    <div class="text-end">
                        <VButtonSubmit
                            type="submit"
                            :isProcessing="form.processing"
                        >
                            Submit
                        </VButtonSubmit>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
