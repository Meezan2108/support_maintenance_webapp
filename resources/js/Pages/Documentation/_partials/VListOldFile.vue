<script setup>
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";

const props = defineProps({
    value: Array,
});

const emits = defineEmits(["update:value"]);
const remove = (delIndex) => {
    const newList = props.value.filter((item, index) => index != delIndex);
    emits("update:value", newList);
};
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
        <div class="ms-auto">
            <VButtonIconDelete
                classStyle="text-danger"
                @onClick="remove(index)"
            />
        </div>
    </div>
</template>
