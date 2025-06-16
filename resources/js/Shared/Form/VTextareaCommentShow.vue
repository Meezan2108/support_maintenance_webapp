<script setup>
import { listStatus } from "@/Config/approvement";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    value: Array,
    activeTab: String,
});

const getComment = (item) => {
    return item.comments ? item.comments[props.activeTab] ?? "" : "";
};

const formatStatus = (status) => {
    const objStatus = listStatus.find((item) => item.id == status);
    return objStatus?.description ?? " - ";
};
</script>

<template>
    <div v-for="item in value" :key="item.id" class="comment-wrapper mb-3">
        <textarea class="form-control comment-textarea" disabled>{{
            getComment(item)
        }}</textarea>
        <div class="comment-button text-end fst-italic text-secondary">
            Written By {{ item.user?.name }}<br />
            at {{ item.date }}<br />
            Status {{ formatStatus(item.status) }}
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

.button-wrapper {
    margin-top: -54px;
}
</style>
