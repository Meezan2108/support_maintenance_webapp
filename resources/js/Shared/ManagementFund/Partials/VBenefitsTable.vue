<script setup>
import { computed, ref } from "vue";
import VModalBenefits from "../Modals/VModalBenefits.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";

const props = defineProps({
    benefits: Array,
    value: Array,
    detailAs: {
        type: String,
        default: "Detail/Remark",
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const emits = defineEmits(["update:value"]);

const benefitsValue = computed({
    get() {
        return props.benefits.map((item) => {
            let selVal = props.value.find(
                (item2) => item2.ref_proposal_benefits_category_id == item.id
            );

            return {
                ref_proposal_benefits_category_id: item.id,
                description: item.description,
                quantity: selVal?.quantity ?? "",
                detail: selVal?.detail ?? "",
            };
        });
    },
});

const clickEdit = (index) => {
    initValue.value = benefitsValue.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};

const save = (value) => {
    let index = editedIndex.value;
    benefitsValue.value[index].quantity = value.quantity;
    benefitsValue.value[index].detail = value.detail;

    emits("update:value", benefitsValue.value);
    isShowForm.value = false;
};

const sumCost = (costYears) => {
    return costYears.reduce((a, b) => parseInt(a) + parseInt(b), 0) ?? 0;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="fw-bold">
                        Research
                        <span v-if="isRequired" class="text-danger">*</span>
                    </th>
                    <th class="fw-bold">Quantity</th>
                    <th class="fw-bold">{{ detailAs }}</th>
                </tr>
                <tr v-for="(item, index) in benefitsValue" :key="item.id">
                    <td>
                        {{ item.description }}
                        <VButtonIconEdit @onClick="clickEdit(index)" />
                    </td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.detail }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <VModalBenefits
        v-if="isShowForm"
        :value="initValue"
        :detailAs="detailAs"
        @onSave="save"
        @onCancel="cancelForm"
    />
</template>
