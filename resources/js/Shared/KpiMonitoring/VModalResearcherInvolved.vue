<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";

import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { USER_ROLE_RESEARCHER } from "@/Config/user.js";

const props = defineProps({
    title: String,
    value: Object,
});

const appBaseUrl = usePage().props.appBaseUrl;

const title = props.value ? "Edit " + props.title : "Add " + props.title;

const elSelectUser = ref(null);
const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    user_id: props.value?.user_id ?? null,
    name: props.value?.name,
});

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    emits("onSave", form.value);
    refModal.value.closeModal();
};

watch(
    () => form.value.user_id,
    (newValue) => {
        form.value.name = elSelectUser.value.getSelGroup().description;
    }
);
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <VSelectAsyncWithLabel
                        ref="elSelectUser"
                        elId="name"
                        label="Name"
                        :error="form.errors?.user_id"
                        v-model:value="form.user_id"
                        searchBy="name"
                        :url="appBaseUrl + '/resources/user'"
                        :filters="{
                            show_all: 1,
                            roles: [USER_ROLE_RESEARCHER],
                        }"
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
