<script setup>
import { Head, useForm } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";

import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";

import _ from "lodash";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    additional: Object,
});

const { urlIndex, urlApprovement, initValue, filters, optionsStatus } =
    props.additional;

const breadcrumbs = [
    {
        url: "#",
        label: "R&D LKM KPI Monitoring",
    },
    {
        url: urlIndex,
        label: "Agency KPI Target",
    },
    {
        url: "#",
        label: "Approvement",
    },
];

const form = useForm({
    approval_status: initValue.kpi_achievement?.approval_status,
    _method: "PUT",
});

const submit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Approval!",
    });

    form.is_submited = 1;
    if (!result.isConfirmed) {
        form.is_submited = 0;
        return false;
    }

    form.post(urlApprovement, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head>
        <title>{{ title }}</title>
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
                        View KPI Achievement
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />

                <div class="row">
                    <div class="col-lg-6 mb-3 order-1 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="user_id"
                            label="Researcher Name"
                            :value="initValue.user.name"
                            :isPlainText="false"
                        />
                    </div>

                    <div class="col-lg-6 mb-3 order-2 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="category"
                            label="Category"
                            :options="arrCategory"
                            :value="initValue.category.description"
                            :isPlainText="false"
                        />
                    </div>

                    <div class="col-lg-6 mb-3 order-1 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="year"
                            label="Year"
                            :value="initValue.year"
                            :isPlainText="false"
                        />
                    </div>

                    <div class="col-lg-6 mb-3 order-2 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="sub_category"
                            label="Sub Category"
                            :value="initValue.sub_category?.description"
                            :isPlainText="false"
                        />
                    </div>

                    <div class="col-lg-6 mb-3 order-2 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="period_id"
                            label="Period"
                            :options="availablePeriod"
                            :value="initValue.period?.description"
                            :isPlainText="false"
                        />
                    </div>

                    <div class="col-lg-6 mb-3 order-2 order-lg-1">
                        <VInputReadonlyWithLabel
                            elId="target"
                            label="Target"
                            :value="initValue.target"
                            :isPlainText="false"
                        />
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div id="comments">
                        <div class="underline-header mt-2 mb-3">
                            <h5>Approval Status</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <VSelectDefaultWithLabel
                                    elId="approval_status"
                                    label=""
                                    v-model:value="form.approval_status"
                                    :options="optionsStatus"
                                    :error="form.errors.approval_status"
                                    :widthLabel="2"
                                    :widthInput="4"
                                />
                            </div>
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
