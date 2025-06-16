<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectAsyncWithLabel from "@/Shared/Form/VSelectAsyncWithLabel.vue";
import { USER_ROLE_RESEARCHER } from "@/Config/user.js";

import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    title: String,
    value: Object,
    type: String,
    isRequired: {
        type: Boolean,
        default: true,
    },
});
/**
 * type: 'picker', 'both', 'text-only'
 */

const title = props.value ? "Edit " + props.title : "Add " + props.title;
const appBaseUrl = usePage().props.appBaseUrl;

const refModal = ref(null);
const refSelUser = ref(null);

const form = ref({
    is_internal: props.value?.user_id ? true : false,
    id: props.value?.id ?? "",
    user_id: props.value?.user_id ?? null,
    name: props.value?.name ?? "",
    organization: props.value?.organization ?? "",
    man_month: props.value?.man_month ?? "",
});

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    emits("onSave", {
        ...form.value,
        name:
            form.value.is_internal || props.type == "picker"
                ? refSelUser.value.getSelGroup().description
                : form.value.name,
    });
    refModal.value.closeModal();
};

watch(form.is_internal, (value) => {
    if (!value) {
        form.user_id = null;
    } else {
        form.name = "";
    }
});
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div v-if="type == 'both'" class="col-9 offset-3 mb-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.is_internal"
                            id="is_internal"
                        />
                        <label class="form-check-label" for="is_internal">
                            Is Internal Team
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        v-if="
                            (!form.is_internal && type == 'both') ||
                            type == 'text-only'
                        "
                        elId="name"
                        label="Name"
                        v-model:value="form.name"
                        type="text"
                        :error="form.errors?.name"
                        :isRequired="isRequired"
                    />
                    <VSelectAsyncWithLabel
                        v-if="
                            (form.is_internal && type == 'both') ||
                            type == 'picker'
                        "
                        ref="refSelUser"
                        elId="user_id"
                        label="Name"
                        v-model:value="form.user_id"
                        :error="form.errors?.user_id"
                        :url="appBaseUrl + '/resources/user'"
                        :filters="{
                            show_all: 1,
                            roles: [USER_ROLE_RESEARCHER],
                        }"
                        :isRequired="isRequired"
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
