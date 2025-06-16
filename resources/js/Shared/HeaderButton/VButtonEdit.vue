<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
});
</script>

<template>
    <Link :href="href" class="btn btn-sm btn-default">
        <i class="fas fa-edit text-warning"></i>
    </Link>
</template>
