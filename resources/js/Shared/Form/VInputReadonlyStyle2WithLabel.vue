<script setup>
import { ref } from "vue";

const props = defineProps({
    label: String,
    value: String | Number,
    unit: {
        type: String,
        default: "",
    },
    type: {
        type: String,
        default: "text",
    },
    isPlainText: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div class="">
        <label v-if="label != ''" class="form-label label-size fw-bold">
            {{ label }}
        </label>
        <input
            :type="type"
            :class="{
                'form-control-plaintext': isPlainText,
                'form-control': !isPlainText,
            }"
            :value="value"
            readonly
        />
    </div>
</template>
