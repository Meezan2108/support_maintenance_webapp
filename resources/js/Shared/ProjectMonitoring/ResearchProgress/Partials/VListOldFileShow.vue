<script setup>
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";

const props = defineProps({
    value: Array,
});
</script>
<template>
    <div
        v-for="(file, index) in value"
        class="d-flex align-item-start px-3 py-2 bg-light rounded-3 mb-2"
    >
        <div>
            <a
                target="_blank"
                :href="file.url"
                class="text-dark text-decoration-none"
            >
                {{ file.file_name }}
            </a>
        </div>
    </div>
</template>
