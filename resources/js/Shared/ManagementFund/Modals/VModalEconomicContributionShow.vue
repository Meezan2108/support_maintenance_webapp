<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditor from "@/Shared/Form/VContentEditor.vue";
import { ref, watch } from "vue";

const props = defineProps({
    value: Object,
});

const title = "Economic Contribution";

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};
</script>

<template>
    <VModal :title="title" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12" v-html="value.description"></div>
            </div>
        </template>
    </VModal>
</template>
