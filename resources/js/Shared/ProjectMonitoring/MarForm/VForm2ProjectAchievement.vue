<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VOutputTable from "./Partials/VOutputTable.vue";
import VPublicationTable from "./Partials/VPublicationTable.vue";
import VCommercializationTable from "./Partials/VCommercializationTable.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    ipr: initValue?.ipr ?? [],
    publications: initValue?.publications ?? [],
    expertise_development: initValue?.expertise_development ?? [],
    prototype: initValue?.prototype ?? [],
    commercialization: initValue?.commercialization ?? [],
    _method: method,
});

const emits = defineEmits(["onNext"]);

const handleClickNext = async () => {
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            emits("onNext", form.data());
        },
    });
};
</script>

<template>
    <h3>Project Achievement</h3>
    <VDevider class="my-3" />

    <div class="mb-4">
        <h6>Intellectual Property Rights</h6>
        <p style="font-style: italic">
            (Patent, Industrial Design, Trademark, Copyrights etc)
        </p>
        <VOutputTable
            title="Intellectual Property Rights"
            v-model:value="form.ipr"
        />
    </div>

    <div class="mb-4">
        <h6>Publications and papers</h6>
        <p style="font-style: italic">
            (International, national, books, citation etc)
        </p>
        <VPublicationTable v-model:value="form.publications" />
    </div>

    <div class="mb-4">
        <h6>Expertise Development</h6>
        <p style="font-style: italic">
            (PhD, Masters, Research Staff with new speciality)
        </p>
        <VOutputTable
            title="Expertise Development"
            v-model:value="form.expertise_development"
        />
    </div>

    <div class="mb-4">
        <h6>Prototype</h6>
        <p style="font-style: italic">
            (Prototype name, type eg. Lab Scale, engineering scale, commercial
            scale etc)
        </p>
        <VOutputTable title="Prototype" v-model:value="form.prototype" />
    </div>

    <div class="mb-4">
        <h6>Commercialisation</h6>
        <p style="font-style: italic">
            (Lincensing, royalty, spin-off, direct sale etc)
        </p>
        <VCommercializationTable v-model:value="form.commercialization" />
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
