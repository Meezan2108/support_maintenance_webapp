<script setup>
import VDevider from "@/Shared/VDevider.vue";

import { useForm } from "@inertiajs/vue3";

import { ref } from "vue";
import VListOldFileShow from "./Partials/VListOldFileShow.vue";

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
</script>

<template>
    <h3>Attachment</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-12 mb-3">
            <VListOldFileShow v-model:value="form.old_files" />
        </div>
    </div>
</template>
