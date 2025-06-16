<script setup>
import { useForm } from "@inertiajs/vue3";

import VDevider from "@/Shared/VDevider.vue";

//import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
//import VContentEditorInlineWithLabel from "@/Shared/Form/VContentEditorInlineWithLabel.vue";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
//import VOptions from "./VOptions.vue";
import { computed } from "vue";

const props = defineProps({
    initialValue: Object,
    arrRepeatType: Array,
    arrCategory: Array,
    urlSubmit: String,
    method: String,
});

const form = useForm({
    is_manual: props.initialValue?.is_manual,
    ref_reminder_category_id: props.initialValue?.ref_reminder_category_id,
    is_now: props.initialValue?.is_now,
    scheduled_at: props.initialValue?.scheduled_at,
    repeat_type: props.initialValue?.repeat_type,
    status: props.initialValue?.status,
    notes: props.initialValue?.notes,
    options: props.initialValue?.options ?? {},
    _method: props.method,
});

const arrMethod = [
    {
        id: 0,
        description: "Auto",
    },
    {
        id: 1,
        description: "Manual",
    },
];

const arrScheduleType = [
    {
        id: 0,
        description: "Scheduled",
    },
    {
        id: 1,
        description: "Now",
    },
];
const availableOption = computed(() => {
    return props.arrRepeatType.find((item) => item.id == form.repeat_type)
        ?.options;
});

const submit = () => {
    form.post(props.urlSubmit, {
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <!-- <div class="row">
            <div class="col-lg-6 mb-3">
                <VSelectWithLabel
                    elId="ref_category_id"
                    label="Report Type"
                    :error="form.errors?.ref_reminder_category_id"
                    v-model:value="form.ref_reminder_category_id"
                    :options="arrCategory"
                />
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-6 mb-3">
                <VInputReadonlyWithLabel
                    label="ST Staff ID"
                    value="Auto Generated"
                    :isPlainText="false"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <VSelectWithLabel
                    elId="is_manual"
                    label="Staff Name"
                    :error="form.errors?.staff_name"
                    v-model:value="form.staff_name"
                    :options="arrMethod"
                />
            </div>
        </div>
        <!-- <template v-if="form.is_manual == 1">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <VSelectWithLabel
                        elId="is_now"
                        label="Send"
                        :error="form.errors?.is_now"
                        v-model:value="form.is_now"
                        :options="arrScheduleType"
                    />
                </div>
            </div>
            <div v-if="form.is_now == 0" class="row">
                <div class="col-lg-6 mb-3">
                    <VInputWithLabel
                        elId="scheduled_at"
                        label="When"
                        type="datetime-local"
                        :error="form.errors?.scheduled_at"
                        v-model:value="form.scheduled_at"
                    />
                </div>
            </div>
        </template>
        <template v-else>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <VSelectWithLabel
                        elId="repeat_type"
                        label="When"
                        :error="form.errors?.repeat_type"
                        v-model:value="form.repeat_type"
                        :options="arrRepeatType"
                    />
                </div>
            </div>
            <template v-if="availableOption">
                <VOptions
                    v-model:value="form.options"
                    :options="availableOption"
                />
            </template>
        </template>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <VContentEditorInlineWithLabel
                    elId="notes"
                    label="Notes"
                    :error="form.errors?.notes"
                    v-model:value="form.notes"
                />
            </div>
        </div> -->
        <VDevider class="my-4" />

        <div class="text-end">
            <VButtonSubmit type="submit" :isProcessing="form.processing">
                Add New ST Member
            </VButtonSubmit>
        </div>
    </form>
</template>
