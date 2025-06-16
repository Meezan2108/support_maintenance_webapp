<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    pagination: Object,
});
</script>

<template>
    <div class="" id="example_info" role="status" aria-live="polite">
        Showing {{ pagination.from }} to {{ pagination.to }} of
        {{ pagination.total }} entries
    </div>
</template>
