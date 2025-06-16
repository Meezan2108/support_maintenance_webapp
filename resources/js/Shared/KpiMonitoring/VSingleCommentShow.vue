<script setup>
import { listStatus } from "@/Config/approvement";

const props = defineProps({
    value: Array,
});

const formatStatus = (status) => {
    const objStatus = listStatus.find((item) => item.id == status);
    return objStatus?.description ?? " - ";
};
</script>

<template>
    <div class="comment-wrapper mb-3">
        <textarea class="form-control comment-textarea" disabled>{{
            value?.comment
        }}</textarea>
        <div class="comment-button text-end fst-italic text-secondary">
            Status
            {{ formatStatus(value?.approval_status) }}
        </div>
    </div>
</template>

<style scoped>
.comment-textarea {
    min-height: 120px;
    max-height: 120px;
}

.comment-wrapper {
    position: relative;
}

.comment-button {
    position: absolute;
    right: 10px;
    bottom: 10px;
    font-size: 0.9rem;
    line-height: 1.2rem;
}
</style>
