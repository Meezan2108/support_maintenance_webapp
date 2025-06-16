<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";

const props = defineProps({
    initialValue: Object,
    urlSubmit: String,
    method: String,
    arrStatus: Array,
});

const form = useForm({
    code: props.initialValue.code,
    description: props.initialValue.description,
    status: props.initialValue.status ?? 1,
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
                    elId="description"
                    label="Description"
                    type="text"
                    :error="form.errors.description"
                    v-model:value="form.description"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <VSelectDefaultWithLabel
                    elId="status"
                    label="Status"
                    :error="form.errors?.status"
                    v-model:value="form.status"
                    :options="arrStatus"
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
