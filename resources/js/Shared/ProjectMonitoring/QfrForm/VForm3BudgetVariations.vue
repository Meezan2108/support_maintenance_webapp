<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";

import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    reasons: initValue?.reasons,
    is_submited: false,
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
    <h3>Budget Variations</h3>
    <p style="font-style: italic">(Please provide the reasons)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorWithLabel
            elId="reasons"
            label="Reasons for variations from budget"
            v-model:value="form.reasons"
            :error="form.errors.reasons"
        />
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
