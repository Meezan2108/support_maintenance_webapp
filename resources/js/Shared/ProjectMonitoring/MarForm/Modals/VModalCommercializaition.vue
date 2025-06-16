<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import { ref, watch } from "vue";
const props = defineProps({
    title: String,
    value: Object,
});

const title = props.value ? "Edit Comercialisation" : "Add Comercialisation";

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    name: props.value?.name ?? "",
    taker: props.value?.taker ?? "",
    category: props.value?.category ?? 1,
    category_description: props.value?.category_description ?? "",
    date: props.value?.date ?? "",
});

const categoryOptions = [
    {
        id: 1,
        description: "Product",
    },
    {
        id: 2,
        description: "Technology",
    },
];

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    const selCategory = categoryOptions.find(
        (item) => item.id == form.value.category
    );
    form.value.category_description = selCategory.description;
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
                        elId="taker"
                        label="Taker"
                        v-model:value="form.taker"
                        type="text"
                        :error="form.errors?.taker"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VSelectDefaultWithLabel
                        elId="category"
                        label="Category"
                        v-model:value="form.category"
                        :options="categoryOptions"
                        :error="form.errors?.category"
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
