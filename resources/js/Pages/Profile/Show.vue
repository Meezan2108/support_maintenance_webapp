<script setup>
import { Head, Link } from "@inertiajs/vue3";
import VHeaderButtonEdit from "@/Shared/HeaderButton/VButtonEdit.vue";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VDevider from "@/Shared/VDevider.vue";
import VContentEditorInlineReadonlyWithLabel from "../../Shared/Form/VContentEditorInlineReadonlyWithLabel.vue";

defineProps({
    user: Object,
    filters: Object,
    canEdit: Boolean,
    urlEdit: String,
    urlIndex: String,
});

const breadcrumbs = [
    {
        url: "#",
        label: "Profile",
    },
];
</script>

<template>
    <Head>
        <title>Profile</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5>Profile User</h5>
                    <div class="btn-wrapper">
                        <VHeaderButtonEdit v-if="canEdit" :href="urlEdit" />
                    </div>
                </div>
                <VDevider />
                <div class="mt-4 mb-3">
                    <label
                        class="select-picture label-pointer d-flex align-items-center justify-content-center"
                    >
                        <img
                            ref="elPicturePreview"
                            class="preview-picture"
                            :src="user.picture"
                            :class="{ 'd-none': !user.picture }"
                        />
                        <span
                            class="material-icons"
                            :class="{ 'd-none': user.picture }"
                        >
                            photo_camera
                        </span>
                    </label>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Staff ID"
                            :value="user.staf_id"
                        />
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Salutation"
                            :value="user.salutation"
                        />
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Name"
                            :value="user.name"
                        />
                    </div>
                    <!-- <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Qualification"
                            :value="user.qualification"
                        />
                    </div> -->
                    <!-- <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Working Address"
                            :value="user.working_address"
                        />
                    </div> -->
                </div>
                <div class="row">
                    <!-- <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="NRIC"
                            :value="user.nric"
                        />
                    </div> -->
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Position"
                            :value="user.position?.description"
                        />
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Division"
                            :value="user.division?.description"
                        />
                    </div> -->
                    <!-- <div class="col-lg-6 mb-3">
                        <VContentEditorInlineReadonlyWithLabel
                            elId="researcher_id"
                            label="Researcher ID"
                            :value="user.researcher_id"
                        />
                    </div> -->
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Roles"
                            :value="
                                user.roles?.map((item) => item.name).join(',')
                            "
                        />
                    </div>
                    <div class="col-lg-6">
                        <VInputReadonlyWithLabel
                            label="Status"
                            :value="user.status_text"
                        />
                    </div>
                </div>

                <VDevider class="my-4" />

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <VInputReadonlyWithLabel
                            label="Email"
                            :value="user.email"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
