<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectDefaultWithLabel from "@/Shared/Form/VSelectDefaultWithLabel.vue";

import { ref, watch } from "vue";
import { LIST_TEAM_TYPE } from "../../../Config/projectTeam";

const props = defineProps({
    title: String,
    value: Object,
});

const ARR_TYPE = [
    {
        id: "",
        description: "Choose Team Type",
    },
    ...LIST_TEAM_TYPE,
];

const title = props.value ? "Edit " + props.title : "Add " + props.title;

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    user_id: props.value?.user_id ?? "",
    name: props.value?.name ?? "",
    type: props.value?.type ?? "",
    organization: props.value?.organization ?? "",
    man_month: props.value?.man_month ?? "",
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
                    <VSelectDefaultWithLabel
                        elId="type"
                        label="Type"
                        v-model:value="form.type"
                        :options="ARR_TYPE"
                        :error="form.errors?.type"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="organization"
                        label="Organization"
                        v-model:value="form.organization"
                        type="text"
                        :error="form.errors?.organization"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="man_month"
                        label="Man Month"
                        v-model:value="form.man_month"
                        type="text"
                        :error="form.errors?.man_month"
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
