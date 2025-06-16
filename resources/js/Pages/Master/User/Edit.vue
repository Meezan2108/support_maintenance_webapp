<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VHeaderButtonInfo from "@/Shared/HeaderButton/VButtonInfo.vue";
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
    user: Object,
    divisions: Array,
    positions: Array,
    roles: Array,
    filters: {
        type: Object,
        default: null,
    },
    canView: Boolean,
    urlShow: String,
    urlUpdate: String,
    urlIndex: String,
    urlUpdateCreds: String,
});

const form = useForm({
    file_picture: null,
    old_picture: props.user.picture,
    staf_id: props.user.staf_id,
    name: props.user.name,
    salutation: props.user.salutation,
    working_address: props.user.working_address,
    qualification: props.user.qualification,
    ref_division_id: props.user.ref_division_id,
    ref_position_id: props.user.ref_position_id,
    roles: props.user.roles.map((item) => item.id),
    status: props.user.status,
    _method: "PUT",
});

const formCreds = useForm({
    email: props.user.email,
    password: "",
    password_confirmation: "",
    _method: "PUT",
});

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
        label: "Edit User",
    },
];

const submit = () => {
    form.post(props.urlUpdate, {
        preserveScroll: true,
        forceFormData: true,
    });
};

const submitCreds = () => {
    formCreds.post(props.urlUpdateCreds, {
        onSuccess: () => {
            formCreds.value.password = "";
            formCreds.value.password_confirmation = "";
        },
    });
};
</script>

<template>
    <Head>
        <title>Edit - User Management</title>
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
                        Edit User
                    </VTitleWithBackLink>
                    <div class="btn-wrapper">
                        <VHeaderButtonInfo v-if="canView" :href="urlShow" />
                    </div>
                </div>
                <VDevider />
                <VAlert />

                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-lg-6 mt-4 mb-3">
                            <VUploadPorfilePictureWithLabel
                                elId="picture"
                                label="Upload Picture"
                                v-model:old_picture="form.old_picture"
                                v-model:value="form.file_picture"
                                :error="form.errors.file_picture"
                            />
                        </div>
                    </div>
                    <div class="row mt-4">
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
                    <div class="row">
                        <!-- <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="salutation"
                                label="Salutation"
                                type="text"
                                :error="form.errors.salutation"
                                v-model:value="form.salutation"
                            />
                        </div> -->
                    </div>
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
                                class="mb-3"
                            />
                        </div> -->
<!-- 
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="working_address"
                                label="Working Address"
                                :error="form.errors.working_address"
                                v-model:value="form.working_address"
                            />
                        </div> -->
                    </div>
                    <div class="row">
                        <!-- <div class="col-lg-6 mb-3">
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
                    </div>
                    <div class="row">
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
                </form>
                <form @submit.prevent="submitCreds">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="email"
                                label="Email"
                                type="email"
                                :error="formCreds.errors.email"
                                v-model:value="formCreds.email"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputPasswordWithLabel
                                elId="password"
                                label="New Password"
                                :error="formCreds.errors.password"
                                v-model:value="formCreds.password"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputPasswordWithLabel
                                elId="password_confirmation"
                                label="New Password Confirmation"
                                :error="formCreds.errors.password_confirmation"
                                v-model:value="formCreds.password_confirmation"
                            />
                        </div>
                        <div class="col-lg-6">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                :disabled="formCreds.processing"
                                @click="submitCreds"
                            >
                                Change Password
                            </button>
                        </div>
                    </div>
                </form>
                <VDevider class="my-4" />

                <div class="text-end">
                    <VButtonSubmit
                        type="button"
                        :isProcessing="form.processing"
                        @onCLickSubmit="submit"
                    >
                        Submit
                    </VButtonSubmit>
                </div>
            </div>
        </div>
    </div>
</template>
