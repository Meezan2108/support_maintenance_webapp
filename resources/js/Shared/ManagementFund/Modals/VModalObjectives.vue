<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditor from "@/Shared/Form/VContentEditor.vue";
import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
});

const title = props.value ? "Edit Objectives" : "Add Objectives";

const refModal = ref(null);
const objective = ref(props.value);

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    emits("onSave", objective.value);
    refModal.value.closeModal();
};
</script>

<template>
    <VModal :title="title" ref="refModal" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12">
                    <VContentEditor
                        elId="objectives_content_editor"
                        v-model:value="objective.description"
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
