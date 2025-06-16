<script setup>
import VModal from "@/Shared/VModal.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

import { formatMonth } from "@/Helpers/date.js";

const props = defineProps({
    value: Object,
});

const title = "Activities";

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
                        label="Activities"
                        :value="value.activities"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="From Date"
                        :value="formatMonth(value.from)"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="To Date"
                        :value="formatMonth(value.to)"
                    />
                </div>
            </div>
        </template>
    </VModal>
</template>
