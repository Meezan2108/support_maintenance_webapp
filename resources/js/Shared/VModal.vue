<script setup>
import { onMounted, ref } from "vue";
import { Modal } from "bootstrap";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: "modal-lg",
    },
});

const modalEl = ref(null);
const modalObject = ref(null);

const emits = defineEmits(["onClose"]);

onMounted(() => {
    modalObject.value = new Modal(modalEl.value);
    modalObject.value.show();

    modalEl.value.addEventListener("hidden.bs.modal", () => emits("onClose"));
});

const closeModal = () => {
    modalObject.value.hide();
};

defineExpose({ closeModal });
</script>

<template>
    <div ref="modalEl" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" :class="size">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="m-3">
                        <button
                            type="button"
                            class="btn-close float-end"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>

                        <h5 class="modal-title">
                            {{ title }}
                        </h5>
                    </div>
                    <slot name="body" />
                </div>

                <div class="modal-footer">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </div>
</template>
