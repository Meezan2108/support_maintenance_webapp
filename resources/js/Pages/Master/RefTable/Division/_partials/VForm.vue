<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";

const props = defineProps({
    initialValue: Object,
    urlSubmit: String,
    method: String,
});

const form = useForm({
    code: props.initialValue.code,
    code2: props.initialValue.code2,
    description: props.initialValue.description,
    _method: props.method,
});

const submit = () => {
    form.post(props.urlSubmit, {
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="row">
            <div class="col-lg-6 mb-3">
                <VInputWithLabel
                    elId="code"
                    label="Code"
                    type="text"
                    :error="form.errors.code"
                    v-model:value="form.code"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <VInputWithLabel
                    elId="code2"
                    label="Code 2"
                    type="text"
                    :error="form.errors.code2"
                    v-model:value="form.code2"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <VInputWithLabel
                    elId="description"
                    label="Description"
                    type="text"
                    :error="form.errors.description"
                    v-model:value="form.description"
                />
            </div>
        </div>
        <VDevider class="my-4" />

        <div class="text-end">
            <VButtonSubmit type="submit" :isProcessing="form.processing">
                Submit
            </VButtonSubmit>
        </div>
    </form>
</template>
