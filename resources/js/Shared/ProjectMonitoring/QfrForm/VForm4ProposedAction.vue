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
    proposed_action: initValue?.proposed_action,
    is_submited: false,
    _method: method,
});

const emits = defineEmits(["onNext"]);

const handleClickNext = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Submit Report!",
    });

    form.is_submited = 0;
    if (result.isConfirmed) {
        form.is_submited = 1;
    }

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            useTaskStore().checkCount();
            useNotificationStore().reloadCount();
        },
    });
};
</script>

<template>
    <h3>Proposed Action</h3>
    <p style="font-style:italic;">(Please give details of the proposed action)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorWithLabel
            elId="proposed_action"
            label="Proposed corrective action"
            v-model:value="form.proposed_action"
            :error="form.errors.proposed_action"
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
