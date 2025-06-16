<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";
import VUploadDocument from "@/Shared/Form/VUploadDocument.vue";

import { ref, watch } from "vue";
import VListNewFile from "./Partials/VListNewFile.vue";
import VListOldFile from "./Partials/VListOldFile.vue";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const selFile = ref(null);

const form = useForm({
    old_files: initValue?.old_files,
    new_files: [],
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

    form.is_submited = 1;
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

watch(selFile, (newValue) => {
    form.new_files.push(newValue);
});
</script>

<template>
    <h3>Report</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-12 mb-3">
            <VUploadDocument
                elId="picture"
                label="Upload Picture"
                v-model:value="selFile"
            />
        </div>
        <div class="col-12 mb-3">
            <VListNewFile v-model:value="form.new_files" />
            <VListOldFile v-model:value="form.old_files" />
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
