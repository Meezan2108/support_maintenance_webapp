<script setup>
import { ref } from "vue";

defineProps({
    search: String,
});

defineEmits(["update:search"]);
</script>

<template>
    <div class="dataTables_filter">
        <label
            >Search:<input
                :value="search"
                type="search"
                class="form-control form-control-sm"
                placeholder=""
                @input="$emit('update:search', $event.target.value)"
        /></label>
    </div>
</template>
