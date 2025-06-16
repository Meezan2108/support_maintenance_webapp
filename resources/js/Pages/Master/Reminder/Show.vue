<script setup>
import { Head, Link } from "@inertiajs/vue3";

import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";
import VTitleWithBackLink from "@/Shared/VTitleWithBackLink.vue";
import VDevider from "@/Shared/VDevider.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VContentEditorInlineReadonlyWithLabel from "@/Shared/Form/VContentEditorInlineReadonlyWithLabel.vue";
import VOptionsShow from "./_partials/VOptionsShow.vue";
import { computed } from "vue";

const props = defineProps({
    title: String,
    additional: Object,
});

const { data, filters, arrRepeatType, urlIndex } = props.additional;

const breadcrumbs = [
    {
        url: urlIndex,
        label: "Reminder",
    },
    {
        url: "#",
        label: "Edit Reminder",
    },
];

const availableOption = computed(() => {
    return arrRepeatType.find((item) => item.id == data.repeat_type)?.options;
});
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <div class="p-3">
        <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <VTitleWithBackLink
                        :href="urlIndex"
                        :filters="filters ?? {}"
                    >
                        Detail Reminder
                    </VTitleWithBackLink>
                </div>
                <VDevider class="mb-4" />
                <div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputReadonlyWithLabel
                                elId="ref_category_id"
                                label="Report Type"
                                :value="data.ref_reminder_category_text"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VInputReadonlyWithLabel
                                elId="is_manual"
                                label="Method"
                                :value="data.is_manual_text"
                            />
                        </div>
                    </div>
                    <template v-if="data.is_manual == 1">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="scheduled_at"
                                    label="When"
                                    :value="data.scheduled_at"
                                />
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <VInputReadonlyWithLabel
                                    elId="repeat_type"
                                    label="When"
                                    v-model:value="data.repeat_type_text"
                                />
                            </div>
                        </div>
                        <template v-if="availableOption">
                            <VOptionsShow
                                v-model:value="data.options"
                                :options="availableOption"
                            />
                        </template>
                    </template>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <VContentEditorInlineReadonlyWithLabel
                                elId="notes"
                                label="Notes"
                                v-model:value="data.notes"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
