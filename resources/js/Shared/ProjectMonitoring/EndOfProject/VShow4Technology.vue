<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import { useTaskStore } from "@/Store/task.js";
import { useNotificationStore } from "@/Store/notification.js";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method } = props.additional;

const form = useForm({
    tech_approach: initValue?.tech_approach,
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
    <h3>Technology Transfer/Commercialisation Approach</h3>
    <p style="font-style:italic;">(Please describe the approach planned to transfer/commercialise the results of the project)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorReadonlyWithLabel
            elId="tech_approach"
            label="Technology Transfer/Commercialisation Approach"
            :value="form.tech_approach"
        />
    </div>
</template>
