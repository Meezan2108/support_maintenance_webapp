<script setup>
import VShow from "@/Pages/KpiMonitoring/SubmitNewKpi/Publications/_partials/VShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>

<template>
    <VShow :initValue="initValue" />
</template>
