<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";

import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
});

const title = props.value ? "Edit Industry Involved" : "Add Industry Involved";

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    name: props.value?.name ?? "",
    role: props.value?.role ?? "",
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
                        elId="name"
                        label="Name"
                        v-model:value="form.name"
                        type="text"
                        :error="form.errors?.name"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="role"
                        label="Role"
                        v-model:value="form.role"
                        type="text"
                        :error="form.errors?.role"
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
