<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectMultipleWithLabel from "@/Shared/Form/VSelectMultipleWithLabel.vue";
import VRadioWithLabel from "@/Shared/Form/VRadioWithLabel.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";
import VSelectTagWithLabel from "@/Shared/Form/VSelectTagWithLabel.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, watch } from "vue";
import axios from "axios";
import _ from "lodash";

import { useFormStore } from "@/Store/form.js";

const user = computed(() => usePage().props.authUser);

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, proposalType } = props.additional;

const form = useForm({
    proposal_type: proposalType,
    ref_type_of_funding_id:
        proposalType == 1 ? 6 : initValue?.ref_type_of_funding_id,
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
    _method: method,
});

const appBaseUrl = usePage().props.appBaseUrl;

const formStore = useFormStore();

const emits = defineEmits(["onNext", "onPrev"]);

const handleClickNext = async () => {
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
            emits("onNext", form.data());
        },
    });
};

const ajaxShowUser = async (id) => {
    const response = await axios.get(`${appBaseUrl}/resources/user/${id}`);
    return response;
};

const resetResearcher = () => {
    form.researcher.name = "";
    form.researcher.nric = "";
    form.researcher.tel_no = "";
    form.researcher.fax_no = "";
    form.researcher.ref_position_id = "";
    form.researcher.ref_division_id = "";
    form.researcher.email = "";

    // form.researcher.institution = "";
    // form.researcher.grade = "";
    form.working_address = "";
};

const arrPersonInCharge = [
    { id: 1, description: "Dharmik Sheth" },
    { id: 2, description: "Wafi Talipudin" },
    { id: 3, description: "Farzana Yussof" },
    { id: 4, description: "Azri Aziz" },
    { id: 5, description: "Amiirul Alimarjafri" },
    { id: 6, description: "Habri Hazizi" },
    { id: 7, description: "Shaheera Misran" },
    { id: 8, description: "Norulbahiah Rahman" },
];

const developer_name = [
    { id: 1, description: "OMNI" },
    { id: 2, description: "Inimedia" },
    { id: 3, description: "In-House" },
];


const populateResearcher = (data) => {
    form.researcher.name = data.name;
    form.researcher.nric = data.nric;
    form.researcher.tel_no = data.tel_no;
    form.researcher.fax_no = data.fax_no;
    form.researcher.ref_position_id = data.ref_position_id;
    form.researcher.ref_division_id = data.ref_division_id;
    form.researcher.email = data.email;

    // form.researcher.institution = "";
    // form.researcher.grade = "";
    form.working_address = data.working_address;
};

const isLeaderInternal = computed(() => {
    return form.project_leader_type == 1;
});

watch(
    () => form.project_leader_type,
    (newValue) => {
        if (newValue == 1) {
            ajaxShowUser(form.user_id).then((response) => {
                const { data } = response.data;
                populateResearcher(data);
            });
        } else {
            resetResearcher();
        }
    }
);

watch(
    () => form.user_id,
    (newValue) => {
        if (form.project_leader_type == 1 && !initValue?.id) {
            ajaxShowUser(newValue).then((response) => {
                const { data } = response.data;
                populateResearcher(data);
            });
        }
    }
);

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Project Identification</h3>
    <VDevider class="my-3" />

    <!-- <div v-if="proposalType == 2" class="row">
        <div class="col-md-6 mb-3">
            <VSelectAsyncWithLabel
                elId="ref_type_of_funding_id"
                label="Type of Funding"
                v-model:value="form.ref_type_of_funding_id"
                :error="form.errors.ref_type_of_funding_id"
                :url="appBaseUrl + '/resources/type-of-funding'"
                :isRequired="true"
            />
        </div>
    </div> -->
    <!-- <div class="row">
        <div class="col-md-6 mb-3">
            <VRadioWithLabel
                elId="project_leader_type"
                label="Project Leader"
                v-model:value="form.project_leader_type"
                type="checkbox"
                :options="[
                    { id: 1, description: 'Internal' },
                    { id: 2, description: 'External' },
                ]"
                :error="form.errors.project_leader_type"
                :isRequired="true"
            />
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputReadonlyWithLabel
                label="Application ID"
                value="Auto Generated"
                :isPlainText="false"
            />
        </div>
    </div>
        <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="project_title"
                label="Project Title"
                v-model:value="form.project_title"
                type="text"
                :error="form.errors.project_title"
                :isRequired="true"
            />
        </div>
    </div>
        <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="location"
                label="Location:"
                v-model:value="form.location"
                type="text "
                :error="form.errors.location"
                :isRequired="true"
            />
        </div>
    </div>
    <br/>
    <!-- <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="project_title"
                label="Project Title"
                v-model:value="form.project_title"
                type="text"
                :error="form.errors.project_title"
                :isRequired="true"
            />
        </div>
    </div> -->
            <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="client_name"
                label="Client Name:"
                v-model:value="form.client_name"
                type="text "
                :error="form.errors.client_name"
                :isRequired="true"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <VInputWithLabel
                elId="client_correspondant"
                label="Client Correspondant:"
                v-model:value="form.client_correspondant"
                type="text "
                :error="form.errors.client_correspondant"
                :isRequired="true"
            />
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-6 mb-3">
            <VSelectAsyncWithLabel
                elId="user_id"
                label="Name"
                v-model:value="form.user_id"
                :error="form.errors.user_id"
                :url="appBaseUrl + '/resources/user'"
                :isRequired="true"
            />
        </div>
    </div> -->
    <br/>
    <br/>
    <h5 class="mb-3 mt-3">Project's Leader Information</h5>
    <VDevider class="my-3" />
    <div class="row">
        <!-- <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputWithLabel
                elId="person_in_charge"
                label="Person In Charge"
                v-model:value="form.person_in_charge"
                type="text"
                :error="form.errors.person_in_charge"
                :isRequired="true"
            />
        </div> -->
        <div class="row">
            <div class="col-lg-6">
                <VSelectDefaultWithLabel
                    elId="person_in_charge"
                    label="Person In Charge"
                    :error="form.errors.arrPersonInCharge"
                    v-model:value="form.arrPersonInCharge"
                    :options="arrPersonInCharge"
                    :isRequired="true"
                />
         </div>
        </div>


        <br/>
        <br/>

         <div class="row">
            <div class="col-lg-6">
                <VSelectDefaultWithLabel
                    elId="developer_name"
                    label="Developer"
                    :error="form.errors.developer_name"
                    v-model:value="form.developer_name"
                    :options="developer_name"
                    :isRequired="true"
                />
         </div>
       </div>

        
<!--         
                          <div class="col-md-6 mb-3 order-0 order-md-0">
                            <VSelectMultipleWithLabel
                                elId="person_in_charge"
                                label="Person In Charge"
                                :error="form.errors.person_in_charge"
                                v-model:value="form.person_in_charge"
                                :options="arrPersonInCharge"
                            />
                        </div> -->
        <!-- <div class="col-md-6 mb-3 order-1 order-md-0">
            <VInputWithLabel
                elId="researcher_address"
                label="Working Address"
                v-model:value="form.working_address"
                type="text"
                :error="form.errors.working_address"
                :isRequired="isLeaderInternal"
            />
        </div> -->

        <!-- <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputWithLabel
                elId="researcher_nric"
                label="Project Leader NRIC"
                v-model:value="form.researcher.nric"
                type="text"
                :error="form.errors['researcher.nric']"
                :isRequired="true"
            />
        </div> -->

        <!-- <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputWithLabel
                elId="researcher_tel_no"
                label="Telephone"
                v-model:value="form.researcher.tel_no"
                type="tel"
                :error="form.errors['researcher.tel_no']"
                :isRequired="true"
            />
        </div> -->
<!-- 
        <div class="col-md-6 mb-3 order-0 order-md-2">
            <VSelectAsyncWithLabel
                elId="ref_position_id"
                label="Position"
                v-model:value="form.researcher.ref_position_id"
                :error="form.errors['researcher.ref_position_id']"
                :url="appBaseUrl + '/resources/position'"
                :isRequired="isLeaderInternal"
            />
        </div> -->

        <!-- <div class="col-md-6 mb-3 order-1 order-md-2">
            <VInputWithLabel
                elId="researcher_fax_no"
                label="Fax"
                v-model:value="form.researcher.fax_no"
                type="tel"
                :error="form.errors['researcher.fax_no']"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-3">
            <VSelectAsyncWithLabel
                elId="ref_division_id"
                label="Division"
                v-model:value="form.researcher.ref_division_id"
                :error="form.errors['researcher.ref_division_id']"
                :url="appBaseUrl + '/resources/division'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-3">
            <VInputWithLabel
                elId="researcher_institution"
                label="Institution"
                v-model:value="form.institution"
                type="text"
                :error="form.errors.institution"
                :isRequired="isLeaderInternal"
            />
        </div> -->

        <!-- <div class="col-md-6 mb-3 order-0 order-md-4">
            <VInputWithLabel
                elId="researcher_grade"
                label="Grade"
                v-model:value="form.grade"
                type="text"
                :error="form.errors.grade"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-4">
            <VInputWithLabel
                elId="researcher_email"
                label="Email"
                v-model:value="form.researcher.email"
                type="email"
                :error="form.errors['researcher.email']"
                :isRequired="true"
            />
        </div>
    </div>
    <VDevider class="mb-4" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <VSelectTagWithLabel
                elId="keywords"
                label="Keywords"
                v-model:value="form.keywords"
                :error="form.errors.keywords"
                :isRequired="isLeaderInternal"
            />
        </div>-->
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>
