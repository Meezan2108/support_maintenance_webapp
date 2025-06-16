<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";

import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    value: Object,
});

const title = props.value
    ? "Edit Publication and paper"
    : "Add Publication and paper";

const refModal = ref(null);
const refSelectPubType = ref(null);

const form = ref({
    id: props.value?.id ?? "",
    title: props.value?.title ?? "",
    publisher: props.value?.publisher ?? "",
    ref_pub_type_id: props.value?.ref_pub_type_id ?? "",
    ref_pub_type_description: props.value?.ref_pub_type_description ?? "",
    date: props.value?.date ?? "",
});

const appBaseUrl = usePage().props.appBaseUrl;

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    const selPubType = refSelectPubType.value.getSelGroup();
    form.value.ref_pub_type_description = selPubType.description;

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
                        elId="title"
                        label="Title"
                        v-model:value="form.title"
                        type="text"
                        :error="form.errors?.title"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="publisher"
                        label="Publisher"
                        v-model:value="form.publisher"
                        type="text"
                        :error="form.errors?.publisher"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VSelectAsyncWithLabel
                        ref="refSelectPubType"
                        elId="ref_pub_type_id"
                        label="Publication Type"
                        v-model:value="form.ref_pub_type_id"
                        :error="form.errors?.ref_pub_type_id"
                        :url="appBaseUrl + '/resources/pub-type'"
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
