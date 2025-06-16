<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";

import VObjectivesTable from "./Partials/VObjectivesTable.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed, watch } from "vue";

import { useFormStore } from "@/Store/form.js";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, proposalType } = props.additional;

const appBaseUrl = usePage().props.appBaseUrl;

const identification = computed(() => props.additional.identification);

const form = useForm({
    project_leader_type: identification.value?.project_leader_type,
    proposal_type: proposalType,
    ref_research_type_id: initValue?.ref_research_type_id,
    ref_research_cluster_id: initValue?.ref_research_cluster_id,
    objectives: initValue?.objectives ?? [],

    ref_seo_category_id: initValue?.ref_seo_category_id,
    ref_seo_group_id: initValue?.ref_seo_group_id,
    ref_seo_area_id: initValue?.ref_seo_area_id,

    for_primary: {
        ref_for_category_id: initValue?.for_primary?.ref_for_category_id,
        ref_for_group_id: initValue?.for_primary?.ref_for_group_id,
        ref_for_area_id: initValue?.for_primary?.ref_for_area_id,
    },
    for_secondary: {
        ref_for_category_id: initValue?.for_secondary?.ref_for_category_id,
        ref_for_group_id: initValue?.for_secondary?.ref_for_group_id,
        ref_for_area_id: initValue?.for_secondary?.ref_for_area_id,
    },
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
    emits("onPrev", form.data());
};

const isLeaderInternal = computed(() => {
    return identification.value?.project_leader_type == 1;
});

watch(
    () => JSON.stringify(form.data()),
    () => {
        formStore.setIsDirty(form.isDirty);
    }
);
</script>
<template>
    <h3>Objective of the Project</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-12 mb-3">
            <VObjectivesTable
                v-model:value="form.objectives"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VSelectAsyncWithLabel
                elId="ref_research_type_id"
                label="Type Of Research"
                v-model:value="form.ref_research_type_id"
                :error="form.errors.ref_research_type_id"
                :url="appBaseUrl + '/resources/research-type'"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-0">
            <VSelectAsyncWithLabel
                elId="ref_seo_category_id"
                label="SEO Category"
                v-model:value="form.ref_seo_category_id"
                :error="form.errors.ref_seo_category_id"
                :url="appBaseUrl + '/resources/seo-category'"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VSelectAsyncWithLabel
                elId="ref_research_cluster_id"
                label="Research Cluster"
                v-model:value="form.ref_research_cluster_id"
                :error="form.errors.ref_research_cluster_id"
                :url="appBaseUrl + '/resources/research-cluster'"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VSelectAsyncWithLabel
                elId="ref_seo_group_id"
                label="SEO Group"
                v-model:value="form.ref_seo_group_id"
                :filters="{ ref_seo_category_id: form.ref_seo_category_id }"
                :error="form.errors.ref_seo_group_id"
                :url="appBaseUrl + '/resources/seo-group'"
                :isRequired="isLeaderInternal"
            />
        </div>
        <div class="col-md-6 mb-3 offset-md-6 order-1 order-md-1">
            <VSelectAsyncWithLabel
                elId="ref_seo_area_id"
                label="SEO Area"
                v-model:value="form.ref_seo_area_id"
                :filters="{ ref_seo_group_id: form.ref_seo_group_id }"
                :error="form.errors.ref_seo_area_id"
                :url="appBaseUrl + '/resources/seo-area'"
                :isRequired="isLeaderInternal"
            />
        </div>
    </div>

    <h4>Field of Research</h4>

    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0"></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <h5>Primary Field of Research</h5>
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-0">
            <h5>Secondary Field of Research</h5>
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VSelectAsyncWithLabel
                elId="primary_ref_for_category_id"
                label="FOR Category"
                v-model:value="form.for_primary.ref_for_category_id"
                :error="form.errors['for_primary.ref_for_category_id']"
                :url="appBaseUrl + '/resources/for-category'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VSelectAsyncWithLabel
                elId="secondary_ref_for_category_id"
                label="FOR Category"
                v-model:value="form.for_secondary.ref_for_category_id"
                :error="form.errors['for_secondary.ref_for_category_id']"
                :url="appBaseUrl + '/resources/for-category'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-2">
            <VSelectAsyncWithLabel
                elId="primary_ref_for_group_id"
                label="FOR Group"
                v-model:value="form.for_primary.ref_for_group_id"
                :error="form.errors['for_primary.ref_for_group_id']"
                :filters="{
                    ref_for_category_id: form.for_primary.ref_for_category_id,
                }"
                :url="appBaseUrl + '/resources/for-group'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-2">
            <VSelectAsyncWithLabel
                elId="secondary_ref_for_group_id"
                label="FOR Group"
                v-model:value="form.for_secondary.ref_for_group_id"
                :error="form.errors['for_secondary.ref_for_group_id']"
                :filters="{
                    ref_for_category_id: form.for_secondary.ref_for_category_id,
                }"
                :url="appBaseUrl + '/resources/for-group'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-3">
            <VSelectAsyncWithLabel
                elId="primary_ref_for_area_id"
                label="FOR Area"
                v-model:value="form.for_primary.ref_for_area_id"
                :error="form.errors['for_primary.ref_for_area_id']"
                :filters="{
                    ref_for_group_id: form.for_primary.ref_for_group_id,
                }"
                :url="appBaseUrl + '/resources/for-area'"
                :isRequired="isLeaderInternal"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-3">
            <VSelectAsyncWithLabel
                elId="secondary_ref_for_area_id"
                label="FOR Area"
                v-model:value="form.for_secondary.ref_for_area_id"
                :error="form.errors['for_secondary.ref_for_area_id']"
                :filters="{
                    ref_for_group_id: form.for_secondary.ref_for_group_id,
                }"
                :url="appBaseUrl + '/resources/for-area'"
                :isRequired="isLeaderInternal"
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
