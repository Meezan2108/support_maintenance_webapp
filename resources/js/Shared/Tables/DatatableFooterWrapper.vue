<script setup>
import { ref, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";

import DatatableLength from "@/Shared/Tables/DatatableLength.vue";
import DatatableInfo from "@/Shared/Tables/DatatableInfo.vue";
import DatatablePagination from "@/Shared/Tables/DatatablePagination.vue";

let props = defineProps({
    pagination: Object,
    filters: Object,
});

const emit = defineEmits(["onChange"]);

const changePageLength = (value) => {
    emit("onChange", value);
};
</script>

<template>
    <div class="row">
        <div
            class="col-sm-12 col-md-6 d-md-flex align-items-baseline mb-md-0 mb-3"
        >
            <DatatableLength
                :activeLength="
                    filters.per_page ? parseInt(filters.per_page) : 20
                "
                @onChange="changePageLength"
            />
            <DatatableInfo
                :pagination="pagination"
                class="ms-lg-4 text-center text-md-start"
            />
        </div>
        <div class="col-sm-12 col-md-6">
            <DatatablePagination :pagination="pagination" />
        </div>
    </div>
</template>
