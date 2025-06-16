<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VRadioReadonlyWithLabel from "@/Shared/Form/VRadioReadonlyWithLabel.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import _ from "lodash";

const user = computed(() => usePage().props.authUser);

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;

const form = useForm({
    project_leader_type: initValue?.project_leader_type,
    project_title: initValue?.project_title,
    user_id: initValue?.user_id ?? user.value.id,
    researcher: {
        name: initValue?.researcher?.name,
        nric: initValue?.researcher?.nric,
        tel_no: initValue?.researcher?.tel_no,
        fax_no: initValue?.researcher?.fax_no,
        ref_position_id: initValue?.researcher?.ref_position_id,
        ref_division_id: initValue?.researcher?.ref_division_id,
        email: initValue?.researcher?.email,
    },
    working_address: initValue?.working_address,
    institution: initValue?.institution,
    grade: initValue?.grade,
    keywords: initValue?.keywords ?? [],
});
</script>
<template>
    <h3>Project Identification</h3>
    <VDevider class="my-3" />

    <div v-if="initValue.proposal_type == 2" class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Type of Funding"
                :value="initValue.type_of_funding?.description"
                :isPlainText="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VRadioReadonlyWithLabel
                elId="project_leader_type"
                label="Project Leader"
                :value="initValue.project_leader_type"
                type="checkbox"
                :options="[
                    { id: 1, description: 'Internal' },
                    { id: 2, description: 'External' },
                ]"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Application ID"
                :value="initValue.application_id"
                :isPlainText="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Project Title"
                :value="initValue.project_title"
                :isPlainText="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Name"
                :value="initValue.user?.name"
                :isPlainText="true"
            />
        </div>
    </div>

    <h5 class="mb-3 mt-3">Project's Leader Information</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Project Leader Name"
                :value="initValue.researcher?.name"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-0">
            <VInputReadonlyWithLabel
                label="Working Address"
                :value="initValue.working_address"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputReadonlyWithLabel
                label="Project Leader NRIC"
                :value="initValue.researcher.nric"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="Telephone"
                :value="initValue.researcher.tel_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-2">
            <VInputReadonlyWithLabel
                label="Position"
                :value="initValue.researcher.position?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-2">
            <VInputReadonlyWithLabel
                label="Fax"
                :value="initValue.researcher.fax_no"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-3">
            <VInputReadonlyWithLabel
                label="Division"
                :value="initValue.researcher.division?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-3">
            <VInputReadonlyWithLabel
                label="Institution"
                :value="initValue.institution"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-4">
            <VInputReadonlyWithLabel
                label="Grade"
                :value="initValue.grade"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-4">
            <VInputReadonlyWithLabel
                label="Email"
                :value="initValue.researcher.email"
                :isPlainText="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Keywords"
                :value="initValue.keywords.join(', ')"
                :isPlainText="true"
            />
        </div>
    </div>
</template>
