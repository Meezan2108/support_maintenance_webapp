<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";

import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
    detailAs: String,
});

const title = `Edit Benefits (${props.value.description})`;

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    quantity: props.value?.quantity ?? "",
    detail: props.value?.detail ?? "",
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
                        elId="quantity"
                        label="Quantity"
                        v-model:value="form.quantity"
                        type="number"
                        :error="form.errors?.quantity"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="detail"
                        :label="detailAs"
                        v-model:value="form.detail"
                        type="text"
                        :error="form.errors?.detail"
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
