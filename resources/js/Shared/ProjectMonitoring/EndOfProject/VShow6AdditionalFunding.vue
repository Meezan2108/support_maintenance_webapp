<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;

const form = useForm({
    additional_fund: initValue?.additional_fund,
});
</script>

<template>
    <h3>Additional Project Funding Obtained</h3>
    <p style="font-style:italic;">(In case of involvement of other funding sources, please indicate the source and total funding provided)</p>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorReadonlyWithLabel
            elId="additional_fund"
            label="Additional Project Funding Obtained"
            :value="form.additional_fund"
        />
    </div>
</template>
