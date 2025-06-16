<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";

import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
});

const title = props.value ? "Edit Milestones" : "Add Milestones";

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    activities: props.value?.activities ?? "",
    from: props.value?.from ?? "",
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
                        elId="activities"
                        label="Milestones"
                        v-model:value="form.activities"
                        type="text"
                        :error="form.errors?.activities"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="from"
                        label="Date"
                        v-model:value="form.from"
                        type="date"
                        :error="form.errors?.from"
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
