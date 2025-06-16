<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";

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
</script>

<template>
    <h3>Proposed Action</h3>
    <p style="font-style:italic;">(Please give details of the proposed action)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorReadonlyWithLabel
            elId="proposed_action"
            label="Proposed corrective action"
            v-model:value="form.proposed_action"
        />
    </div>
</template>
