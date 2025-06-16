<script setup>
import { ref } from "vue";

const props = defineProps({
    label: String,
    value: String | Number,
    unit: {
        type: String,
        default: "",
    },
    isPlainText: {
        type: Boolean,
        default: true,
    },

    widthLabel: {
        type: Number,
        default: 3,
    },
    widthInput: {
        type: Number,
        default: 9,
    },
});
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            v-if="label != ''"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size text-sm-end fw-bold mb-sm-0 mb-2'
            "
        >
            {{ label }}
        </label>
        <div :class="'col-sm-' + widthInput + ' custom-position-relative'">
            <input
                type="text"
                :class="{
                    'form-control-plaintext': isPlainText,
                    'form-control': !isPlainText,
                }"
                :value="value"
                readonly
            />
            <span v-if="unit != ''" class="custom-input-unit">{{ unit }}</span>
        </div>
    </div>
</template>
