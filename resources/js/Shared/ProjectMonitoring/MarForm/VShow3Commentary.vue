<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;
</script>

<template>
    <h3>Commentary</h3>
    <VDevider class="my-3" />
    <div class="col-12 mb-3">
        <VContentEditorReadonlyWithLabel
            label="Comments"
            :value="initValue.comments"
        />
    </div>
</template>
