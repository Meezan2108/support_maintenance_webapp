<script setup>
import { computed, ref } from "vue";
import VModalBenefitsShow from "../Modals/VModalBenefitsShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";

const props = defineProps({
    benefits: Array,
    value: Array,
    detailAs: {
        type: String,
        default: "Detail/Remark",
    },
});

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

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

const clickShow = (index) => {
    initValue.value = benefitsValue.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th class="fw-bold">Research</th>
                    <th class="fw-bold">Quantity</th>
                    <th class="fw-bold">{{ detailAs }}</th>
                </tr>
                <tr v-for="(item, index) in benefitsValue" :key="item.id">
                    <td>
                        {{ item.description }}
                        <VButtonIconShow @onClick="clickShow(index)" />
                    </td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.detail }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <VModalBenefitsShow
        v-if="isShowForm"
        :value="initValue"
        :detailAs="detailAs"
        @onCancel="cancelForm"
    />
</template>
