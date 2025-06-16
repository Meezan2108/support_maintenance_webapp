<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
});
</script>

<template>
    <Link :href="href" class="btn btn-light rounded-06">
        <span class="material-icons me-1">download</span>
        <slot />
    </Link>
</template>
