<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { ref } from "vue";

const props = defineProps({
    title: String,
    value: Array,
});

const title = props.title ?? "Objectives";

const refModal = ref(null);

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-lg" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <div v-if="value.length > 0">
                        <div
                            v-for="(item, index) in value"
                            :key="index"
                            v-html="item.description"
                            class="border border-secondary content-editor-show p-2 mb-3"
                        ></div>
                    </div>
                    <div v-else class="text-center py-3 text-secondary">
                        <h3 class="text-center">
                            <span
                                class="material-icons"
                                style="font-size: 40pt"
                            >
                                content_paste_search
                            </span>
                        </h3>

                        <strong>There is no objectives!</strong>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:footer>
            <VButton @onClick="refModal.closeModal()"> Close </VButton>
        </template>
    </VModal>
</template>
