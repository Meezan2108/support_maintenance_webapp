<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";

import { ref, watch } from "vue";

const props = defineProps({
    title: String,
    value: Array,
});

const title = props.title ?? "Project Team";

const refModal = ref(null);

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const formatType = (type) => {
    if (type == 1) return "Project Leader";
    if (type == 2) return "Researcher";
    return "Staff";
};
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-lg" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="45%" class="fw-bold">Name</th>
                                <th width="25" class="fw-bold">Type</th>
                                <th width="25%" class="fw-bold">
                                    Organization
                                </th>
                            </tr>
                            <tr v-for="(item, index) in value" :key="index">
                                <td>{{ item.name }}</td>
                                <td>{{ formatType(item.type) }}</td>
                                <td>{{ item.organization }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <template v-slot:footer>
            <VButton @onClick="refModal.closeModal()"> Close </VButton>
        </template>
    </VModal>
</template>
