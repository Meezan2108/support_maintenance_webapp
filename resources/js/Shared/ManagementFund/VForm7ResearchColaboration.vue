<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VIndustriesTable from "./Partials/VIndustriesTable.vue";
import VInstitutionTable from "./Partials/VInstitutionTable.vue";
import VProjectTeamTable from "./Partials/VProjectTeamTable.vue";
import { computed, watch } from "vue";

import { useFormStore } from "@/Store/form.js";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, proposalType } = props.additional;

const identification = computed(() => props.additional.identification);

const form = useForm({
    proposal_type: proposalType,
    project_leader_type: identification.value?.project_leader_type,
    organizations: initValue?.organizations ?? [],
    industries: initValue?.industries ?? [],
    project_leaders: initValue?.project_leaders ?? [],
    researchers: initValue?.researchers ?? [],
    staffs: initValue?.staffs ?? [],
    _method: method,
});

const formStore = useFormStore();

const emits = defineEmits(["onNext", "onPrev"]);

const handleClickNext = () => {
    form.project_leader_type = identification.value?.project_leader_type;
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            formStore.reset();
            emits("onNext", form.data());
        },
    });
};

const handleClickPrev = () => {
    emits("onPrev", form);
};

watch(
    () => form.data(),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Research Collaboration</h3>
    <VDevider class="my-3" />

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Institution Involved in the Project</h6>
            <VInstitutionTable v-model:value="form.organizations" />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Industries Involved in the Project</h6>
            <VIndustriesTable v-model:value="form.industries" />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 mb-3">
            <h6>Project Team</h6>
            <VProjectTeamTable
                v-model:value="form.project_leaders"
                title="Project Leader"
                :userType="proposalType == 1 ? 'picker' : 'both'"
                :isRequired="true"
            />

            <VProjectTeamTable
                v-model:value="form.researchers"
                title="Researcher"
                :userType="proposalType == 1 ? 'picker' : 'both'"
                :isRequired="false"
            />

            <VProjectTeamTable
                v-model:value="form.staffs"
                title="Support Staff Type"
                userType="text-only"
                :isRequired="false"
            />
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButton class="me-2" type="button" @onClick="handleClickPrev">
            Back
        </VButton>
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>

<style scoped>
table th {
    border-color: #dee2e6;
    border-bottom-width: 1px !important;
    text-transform: uppercase;
}
</style>
