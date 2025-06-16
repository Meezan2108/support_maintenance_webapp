<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";

const props = defineProps({
    initialValue: Object,
    urlSubmit: String,
    method: String,
});


// const location = [
//     { id: 1, description: "Brunei Darussalam (BN)" },
//     { id: 2, description: "Kuala Lumpur (MY)" },
//     { id: 3, description: "Kota Kinabalu (MY)" },
// ];

const form = useForm({
    location_name: props.initialValue.location_name ?? '',
    code: props.initialValue.code ?? '',
    _method: props.method, // Only if updating
});

// const submit = () => {
//     form.post(props.urlSubmit, {
//         preserveScroll: true,
//     });
// };

const emit = defineEmits(['close']);

const submit = () => {
    form.post('/panel/ref-table/location', {
        preserveScroll: true,
        onSuccess: () => {
            emit('close'); // Tell the parent to close the modal
        },
    });
};

</script>

<template>
    <form @submit.prevent="submit">
         <!-- <div class="row">
            <div class="col-lg-6">
                <VSelectDefaultWithLabel
                    elId="location"
                    label="Location"
                    :error="form.errors.location"
                    v-model:value="form.location"
                    :options="location"
                    :isRequired="true"
                />
         </div>
       </div> -->

        <div class="row">
            <div class="col-md-6 mb-3">
                <VInputReadonlyWithLabel
                    label="Location ID"
                    value="Auto Generated"
                    :isPlainText="false"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <VInputWithLabel
                    elId="location-name"
                    label="Location Name"
                    type="text"
                    :error="form.errors.location_name"
                    v-model:value="form.location_name"
                />
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-lg-6 mb-3">
                <VInputWithLabel
                    elId="correspondants-name"
                    label="Correspondants Name"
                    type="text"
                    :error="form.errors.description"
                    v-model:value="form.description"
                />
            </div>
        </div> -->
        <VDevider class="my-4" />

        <div class="text-end">
            <VButtonSubmit type="submit" :isProcessing="form.processing">
                Submit
            </VButtonSubmit>
        </div>
    </form>
</template>
