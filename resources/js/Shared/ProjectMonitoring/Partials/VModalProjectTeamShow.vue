<script setup>
import VModal from "@/Shared/VModal.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import { LIST_TEAM_TYPE } from "@/Config/projectTeam";

defineProps({
    title: String,
    value: Object,
});

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const formatTeamType = (typeId) => {
    const type = LIST_TEAM_TYPE.find((item) => item.id == typeId);
    return type?.description ?? " - ";
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
                        label="Type"
                        :value="formatTeamType(value.type)"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Organization"
                        :value="value.organization"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Man Month"
                        :value="value.man_month"
                    />
                </div>
            </div>
        </template>
    </VModal>
</template>
