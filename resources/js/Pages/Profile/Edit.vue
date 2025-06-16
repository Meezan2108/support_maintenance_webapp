<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import VDevider from "@/Shared/VDevider.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VInputPasswordWithLabel from "@/Shared/Form/VInputPasswordWithLabel.vue";
import VUploadPorfilePictureWithLabel from "@/Shared/Form/VUploadPorfilePictureWithLabel.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VAlert from "@/Shared/VAlert.vue";
import VContentEditorInlineWithLabel from "../../Shared/Form/VContentEditorInlineWithLabel.vue";

const props = defineProps({
    user: Object,
    hasPassword: Boolean,
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
    urlUpdateCreds: String,
});

const form = useForm({
    file_picture: null,
    old_picture: props.user.picture,
    staf_id: props.user.staf_id,
    name: props.user.name,
    nric: props.user.nric,
    salutation: props.user.salutation,
    working_address: props.user.working_address,
    qualification: props.user.qualification,
    ref_division_id: props.user.ref_division_id,
    ref_position_id: props.user.ref_position_id,
    researcher_id: props.user.researcher_id,
    roles: props.user.roles.map((item) => item.id),
    status: props.user.status,
    _method: "PUT",
});

const formCreds = useForm({
    email: props.user.email,
    password_old: "",
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
        url: props.urlShow,
        label: "Profile",
    },
    {
        url: "#",
        label: "Edit Profile",
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
            formCreds.password_old = "";
            formCreds.password = "";
            formCreds.password_confirmation = "";
        },
    });
};
</script>

<template>
    <Head>
        <title>Edit - Profile</title>
    </Head>
    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink :href="urlShow">
                        Edit Profile
                    </VTitleWithBackLink>
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
                            <VInputReadonlyWithLabel
                                elId="staf_id"
                                label="Staff ID"
                                type="text"
                                :isPlainText="false"
                                :value="form.staf_id"
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
                            />
                        </div> -->
                        <div class="col-lg-6 mb-3">
                            <!-- <VInputWithLabel
                                elId="working_address"
                                label="Working Address"
                                type="text"
                                :error="form.errors.working_address"
                                v-model:value="form.working_address"
                            />
                        </div> -->
                    </div>
                    <div class="row">
                        <!-- <div class="col-lg-6 mb-3">
                            <VInputWithLabel
                                elId="nric"
                                label="NRIC"
                                type="text"
                                :error="form.errors.nric"
                                v-model:value="form.nric"
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
                        <!-- <div class="col-lg-6 mb-3">
                            <VSelectWithLabel
                                elId="division"
                                label="Division"
                                :error="form.errors.ref_division_id"
                                v-model:value="form.ref_division_id"
                                :options="divisions"
                            />
                        </div> -->
                        <!-- <div class="col-lg-6 mb-3">
                            <VContentEditorInlineWithLabel
                                elId="researcher_id"
                                label="Researcher ID"
                                :error="form.errors.researcher_id"
                                v-model:value="form.researcher_id"
                            /> --->
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

                    <div class="row" v-if="hasPassword">
                        <div class="col-lg-6 mb-3">
                            <VInputPasswordWithLabel
                                elId="password_old"
                                label="Old Password"
                                :error="formCreds.errors.password_old"
                                v-model:value="formCreds.password_old"
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
