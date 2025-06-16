<script setup>
const props = defineProps({
    option: String,
    isChecked: Boolean,
});
</script>

<template>
    <div class="form-check">
        <span class="material-icons form-check-show">
            {{ isChecked ? "check_box" : "check_box_outline_blank" }}
        </span>
        <label class="form-check-label">
            {{ option }}
        </label>
    </div>
</template>
