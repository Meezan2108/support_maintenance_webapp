<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    btnStyle: {
        type: String,
        default: "btn-light",
    },
});
</script>

<template>
    <Link :href="href" class="btn rounded-06" :class="btnStyle">
        <slot />
    </Link>
</template>
