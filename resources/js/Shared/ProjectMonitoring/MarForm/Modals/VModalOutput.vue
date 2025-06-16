<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";

import { ref, watch } from "vue";

const props = defineProps({
    title: String,
    value: Object,
});

const title = props.value ? "Edit " + props.title : "Add " + props.title;

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    output: props.value?.output ?? "",
    date: props.value?.date ?? "",
});

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    emits("onSave", form.value);
    refModal.value.closeModal();
};
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="Output"
                        label="Output"
                        v-model:value="form.output"
                        type="text"
                        :error="form.errors?.output"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="date"
                        label="Date"
                        v-model:value="form.date"
                        type="date"
                        :error="form.errors?.date"
                    />
                </div>
            </div>
        </template>

        <template v-slot:footer>
            <VButton @onClick="refModal.closeModal()"> Cancel </VButton>
            <VButtonSubmit type="button" @onCLickSubmit="save">
                Save
            </VButtonSubmit>
        </template>
    </VModal>
</template>
