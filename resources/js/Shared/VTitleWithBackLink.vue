<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    filters: Object,
    content: String,
});
</script>
<template>
    <h5 class="d-flex align-items-center">
        <Link :href="href" :data="filters ?? {}" class="btn btn-sm btn-default">
            <i class="fas fa-chevron-left"></i>
        </Link>
        <slot />
    </h5>
</template>
