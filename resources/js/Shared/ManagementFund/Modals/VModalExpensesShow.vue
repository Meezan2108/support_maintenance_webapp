<script setup>
import VModal from "@/Shared/VModal.vue";
import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import { formatNumber, getIntValue } from "@/Helpers/number";
import { computed, ref, watch } from "vue";

const props = defineProps({
    title: String,
    years: Array,
    value: Object,
});

const title = `${props.title} Expenses`;

const sumCost = computed(() => {
    return props.value.years.reduce((a, b) => parseInt(a) + parseInt(b), 0);
});

const emits = defineEmits(["onCancel"]);

const cancel = () => {
    emits("onCancel");
};
</script>

<template>
    <VModal :title="title" ref="refModal" size="modal-md" @onClose="cancel">
        <template v-slot:body>
            <div class="row m-2">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Description"
                        :value="value.description"
                    />
                </div>
                <div
                    v-for="(year, index) in years"
                    :key="year"
                    class="col-12 mb-1"
                >
                    <VInputReadonlyWithLabel
                        :label="year"
                        :value="formatNumber(getIntValue(value.years[index]))"
                    />
                </div>
                <VDevider />
                <div class="col-12 mb-3 mt-1">
                    <VInputReadonlyWithLabel
                        label="Total"
                        :value="formatNumber(sumCost)"
                    />
                </div>
            </div>
        </template>
    </VModal>
</template>
