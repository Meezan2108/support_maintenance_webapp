<script setup>
import VModal from "@/Shared/VModal.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

const props = defineProps({
    value: Object,
});

const title = "Organization Involved";

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
                    <VInputReadonlyWithLabel label="Name" :value="value.name" />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Other"
                        :value="value.other"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel label="Role" :value="value.role" />
                </div>
            </div>
        </template>
    </VModal>
</template>
