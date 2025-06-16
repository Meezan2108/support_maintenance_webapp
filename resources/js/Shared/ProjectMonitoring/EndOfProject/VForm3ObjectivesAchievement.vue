<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";

import { useForm } from "@inertiajs/vue3";
import VObjectivesTableShow from "./Partials/VObjectivesTableShow.vue";
import VObjectivesTable from "./Partials/VObjectivesTable.vue";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    original_objectives: initValue?.original_objectives ?? [],
    objectives_achieved: initValue?.objectives_achieved ?? [],
    objectives_not_achieved: initValue?.objectives_not_achieved ?? [],
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
    <h3>Objectives Achievement</h3>
    <VDevider class="my-3" />

    <div class="col-12 mb-3">
        <VObjectivesTableShow
            label="Objectives Achievement"
            subLabel="(Please state the specific project objectives as described in Section II of the Application Form)"
            :value="initValue.original_objectives"
        />
    </div>
    <div class="col-12 mb-3">
        <VObjectivesTable
            label="Objectives Achieved"
            subLabel="(Please state the extent to which the project objectives were achieved)"
            v-model:value="form.objectives_achieved"
        />
    </div>
    <div class="col-12 mb-3">
        <VObjectivesTable
            label="Objectives Not Achieved"
            subLabel="(Please identify the objectives that were not achieved and give reasons)"
            v-model:value="form.objectives_not_achieved"
        />
    </div>
    <VDevider class="mb-4" />
    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>
