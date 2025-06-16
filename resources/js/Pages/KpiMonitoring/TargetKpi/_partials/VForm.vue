<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import Swal from "sweetalert2";
import { computed } from "vue";

const props = defineProps({
    initValue: Object,
    urlSubmit: String,
    method: String,
    arrPeriod: Array,
    arrCategory: Array,
    arrSubCategory: Array,
    user: Object,
});

const form = useForm({
    user_id: props.initValue?.user_id,
    year: props.initValue?.year,
    period_id: props.initValue?.period_id,
    category_id: props.initValue?.category_id,
    sub_category_id: props.initValue?.sub_category_id,
    target: props.initValue?.target,
    is_submited: false,
    _method: props.method,
});

const availablePeriod = computed(() => {
    const category = props.arrCategory.find(
        (item) => item.id == form.category_id
    );

    if (!category) return [];
    return props.arrPeriod.filter((item) => category.type == item.type);
});

const availableSubCategory = computed(() => {
    const category = props.arrCategory.find(
        (item) => item.id == form.category_id
    );

    if (!category) return [];
    return props.arrSubCategory.filter((item) => category.id == item.parent_id);
});

const submit = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonColor: "#28A745",
        cancelButtonColor: "#dfdfdf",
        confirmButtonText: "Yes, Submit!",
    });

    form.is_submited = 1;
    if (!result.isConfirmed) {
        form.is_submited = 0;
        return false;
    }

    form.post(props.urlSubmit, {
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="row">
            <div class="col-lg-6 mb-3 order-1 order-lg-1">
                <VInputReadonlyWithLabel
                    elId="user_id"
                    label="Researcher Name"
                    v-model:value="user.name"
                    :isPlainText="false"
                />
            </div>

            <div class="col-lg-6 mb-3 order-2 order-lg-1">
                <VSelectWithLabel
                    elId="category"
                    label="Category"
                    :options="arrCategory"
                    v-model:value="form.category_id"
                    :isPlainText="false"
                />
            </div>

            <div class="col-lg-6 mb-3 order-1 order-lg-1">
                <VInputWithLabel
                    elId="year"
                    label="Year"
                    type="number"
                    v-model:value="form.year"
                    :isPlainText="false"
                />
            </div>

            <div class="col-lg-6 mb-3 order-2 order-lg-1">
                <VSelectWithLabel
                    v-if="availableSubCategory.length > 0"
                    elId="sub_category"
                    label="Sub Category"
                    :options="availableSubCategory"
                    v-model:value="form.sub_category_id"
                    :isPlainText="false"
                />
            </div>

            <div class="col-lg-6 mb-3 order-2 order-lg-1">
                <VSelectWithLabel
                    elId="period_id"
                    label="Period"
                    :options="availablePeriod"
                    v-model:value="form.period_id"
                    :isPlainText="false"
                />
            </div>

            <div class="col-lg-6 mb-3 order-2 order-lg-1">
                <VInputWithLabel
                    elId="target"
                    label="Target"
                    type="number"
                    v-model:value="form.target"
                    :isPlainText="false"
                />
            </div>
        </div>
        <VDevider class="my-4" />

        <div class="text-end">
            <VButtonSubmit type="submit" :isProcessing="form.processing">
                Submit
            </VButtonSubmit>
        </div>
    </form>
</template>
