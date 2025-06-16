<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";

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
    <p style="font-style:italic;">(Please provide the reasons)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorReadonlyWithLabel
            elId="reasons"
            label="Reasons for variations from budget"
            :value="form.reasons"
        />
    </div>
</template>
