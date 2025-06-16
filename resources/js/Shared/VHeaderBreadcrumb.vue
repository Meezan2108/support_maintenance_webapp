<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    breadcrumbs: Array,
});
</script>

<template>
    <div class="card mb-3">
        <div class="card-body">
            <h6
                class="mb-0 d-inline"
                v-for="(item, index) in breadcrumbs"
                :key="index"
            >
                <Link
                    v-if="item.url != '#'"
                    :href="item.url"
                    class="text-black text-decoration-none"
                >
                    {{ item.label }}
                </Link>
                <span v-else>{{ item.label }}</span>

                <span v-if="index < breadcrumbs.length - 1"> &gt; </span>
            </h6>
        </div>
    </div>
</template>
