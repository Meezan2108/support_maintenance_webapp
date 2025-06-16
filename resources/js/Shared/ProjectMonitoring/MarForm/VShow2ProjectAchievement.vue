<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VOutputTableShow from "./Partials/VOutputTableShow.vue";
import VCommercializationTableShow from "./Partials/VCommercializationTableShow.vue";
import VPublicationTableShow from "./Partials/VPublicationTableShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>

<template>
    <h3>Project Achievement</h3>
    <VDevider class="my-3" />

    <div class="mb-4">
        <h6>Intellectual Property Rights</h6>
        <p style="font-style:italic;">(Patent, Industrial Design, Trademark, Copyrights etc)</p>
        <VOutputTableShow
            title="Intellectual Property Rights"
            :value="initValue.ipr"
        />
    </div>

    <div class="mb-4">
        <h6>Publications and papers</h6>
        <p style="font-style:italic;">(International, national, books, citation etc)</p>
        <VPublicationTableShow :value="initValue.publications" />
    </div>

    <div class="mb-4">
        <h6>Expertise Development</h6>
        <p style="font-style:italic;">(PhD, Masters, Research Staff with new speciality)</p>
        <VOutputTableShow
            title="Expertise Development"
            v-model:value="initValue.expertise_development"
        />
    </div>

    <div class="mb-4">
        <h6>Prototype</h6>
        <p style="font-style:italic;">(Prototype name, type eg. Lab Scale, engineering scale, commercial scale etc)</p>
        <VOutputTableShow title="Prototype" :value="initValue.prototype" />
    </div>

    <div class="mb-4">
        <h6>Commercialisation</h6>
        <p style="font-style:italic;">(Lincensing, royalty, spin-off, direct sale etc)</p>
        <VCommercializationTableShow :value="initValue.commercialization" />
    </div>
</template>
