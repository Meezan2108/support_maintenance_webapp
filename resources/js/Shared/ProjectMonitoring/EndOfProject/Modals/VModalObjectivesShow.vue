<script setup>
import VModal from "@/Shared/VModal.vue";

const props = defineProps({
    value: Object,
});

const title = "Objectives";

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};
</script>

<template>
    <VModal :title="title" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12" v-html="value.description"></div>
            </div>
        </template>
    </VModal>
</template>
