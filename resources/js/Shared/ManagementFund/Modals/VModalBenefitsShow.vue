<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
    detailAs: String,
});

const title = `Benefits: (${props.value.description})`;

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};
</script>

<template>
    <VModal :title="title" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Quantity"
                        :value="value.quantity"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="detail"
                        :label="detailAs"
                        :value="value.detail"
                    />
                </div>
            </div>
        </template>
    </VModal>
</template>
