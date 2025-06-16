<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

import VObjectivesTableShow from "./Partials/VObjectivesTableShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>
<template>
    <h3>Objective of the Project</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-12 mb-3">
            <VObjectivesTableShow :value="initValue.objectives" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Type Of Research"
                :value="initValue.research_type?.description"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-0">
            <VInputReadonlyWithLabel
                label="SEO Category"
                :value="initValue.seo_category?.description"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputReadonlyWithLabel
                label="Research Cluster"
                :value="initValue.research_cluster?.description"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="SEO Group"
                :value="initValue.seo_group?.description"
                :isPlainText="true"
            />
        </div>
        <div class="col-md-6 mb-3 offset-md-6 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="SEO Area"
                :value="initValue.seo_area?.description"
                :isPlainText="true"
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
            <VInputReadonlyWithLabel
                label="FOR Category"
                :value="initValue.for_primary?.category?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="FOR Category"
                :value="initValue.for_secondary?.category?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-2">
            <VInputReadonlyWithLabel
                label="FOR Group"
                :value="initValue.for_primary?.group?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-2">
            <VInputReadonlyWithLabel
                label="FOR Group"
                :value="initValue.for_secondary?.group?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-3">
            <VInputReadonlyWithLabel
                label="FOR Area"
                :value="initValue.for_primary?.area?.description"
                :isPlainText="true"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-3">
            <VInputReadonlyWithLabel
                label="FOR Area"
                :value="initValue.for_secondary?.area?.description"
                :isPlainText="true"
            />
        </div>
    </div>
</template>
