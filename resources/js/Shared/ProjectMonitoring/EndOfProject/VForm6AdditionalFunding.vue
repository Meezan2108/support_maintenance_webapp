<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorWithLabel from "@/Shared/Form/VContentEditorWithLabel.vue";

import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    additional_fund: initValue?.additional_fund,
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
    <h3>Additional Project Funding Obtained</h3>
    <p style="font-style:italic;">(In case of involvement of other funding sources, please indicate the source and total funding provided)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorWithLabel
            elId="additional_fund"
            label="Additional Project Funding Obtained"
            v-model:value="form.additional_fund"
            :error="form.errors.additional_fund"
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
