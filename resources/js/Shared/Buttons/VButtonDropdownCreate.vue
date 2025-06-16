<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    listLink: Array,
});
</script>

<template>
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle rounded-06" type="button" id="dropdownMenuButton1"
            data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-icons me-1">add</span>
            <slot />
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li v-for="item in listLink">
                <Link class="dropdown-item" :href="item.href">
                {{ item.description }}
                </Link>
            </li>
        </ul>
    </div>
</template>
