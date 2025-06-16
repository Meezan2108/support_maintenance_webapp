<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

import { ref, watch } from "vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

const props = defineProps({
    additional: Object,
});

const { initValue, arrProjectStatus, urlSubmit, method } = props.additional;

const selFile = ref(null);

const form = useForm({
    status: props.additional.initValue.status,
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

    if (!result.isConfirmed) {
        return false;
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
    <h3>Status</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-6 mb-3">
            <VSelectDefaultWithLabel
                label="Status"
                v-model:value="form.status"
                :options="arrProjectStatus"
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
