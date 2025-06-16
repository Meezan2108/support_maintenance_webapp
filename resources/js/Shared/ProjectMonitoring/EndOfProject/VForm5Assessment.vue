<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";

import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    asses_research: initValue?.asses_research,
    asses_schedule: initValue?.asses_schedule,
    asses_cost: initValue?.asses_cost,
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
    <h3>Assessment</h3>

    <VDevider class="my-3" />
    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="asses_research"
                label="Assessment of Research Approach"
                subLabel="(Please highlight the main steps actually performed and indicate any major departure from the planned approach or any major difficulty encountered)"
                v-model:value="form.asses_research"
                :error="form.errors.asses_research"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="asses_schedule"
                label="Assessment of the Project Schedule"
                subLabel="(Please make any relevant comment regarding the actual duration of the project and highlight any significant variation from plan)"
                v-model:value="form.asses_schedule"
                :error="form.errors.asses_schedule"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorWithLabel
                elId="asses_cost"
                label="Assessment of Project Costs"
                subLabel="(Please comment on the appropriateness of the original budget and highlight any major departure from the planned budget)"
                v-model:value="form.asses_cost"
                :error="form.errors.asses_cost"
            />
        </div>
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
