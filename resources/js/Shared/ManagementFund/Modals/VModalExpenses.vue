<script setup>
import VModal from "@/Shared/VModal.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

import { computed, ref, watch } from "vue";
import {
    sumCost as numberSumCost,
    formatNumber,
    getIntValue,
} from "@/Helpers/number";

const props = defineProps({
    title: String,
    years: Array,
    value: Object,
});

const title = props.value
    ? `Edit ${props.title} Expenses`
    : `Add ${props.title} Expenses`;

const refModal = ref(null);
const form = ref({
    id: props.value?.id ?? "",
    description: props.value?.description ?? "",
    years: props.value?.years ?? props.years.map((item) => 0),
    total: 0,
});

const sumCost = computed(() => {
    return numberSumCost(form.value.years);
});

const emits = defineEmits(["onSave", "onCancel"]);

const cancel = () => {
    emits("onCancel");
};

const save = () => {
    form.value.years = form.value.years.map((item) => (item == "" ? 0 : item));
    emits("onSave", form.value);
    refModal.value.closeModal();
};
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <VInputWithLabel
                        elId="description"
                        label="Description"
                        v-model:value="form.description"
                        type="text"
                        :error="form.errors?.description"
                    />
                </div>
                <div
                    v-for="(year, index) in years"
                    :key="year"
                    class="col-12 mb-3"
                >
                    <VInputWithLabel
                        :elId="'cost_' + year"
                        :label="year"
                        v-model:value="form.years[index]"
                        type="number"
                        :error="form.errors?.years[index]"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="total"
                        label="Total"
                        :value="formatNumber(sumCost)"
                        :isPlainText="false"
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
