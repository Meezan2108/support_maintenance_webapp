<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import _ from "lodash";

const props = defineProps({
    isShowValidation: {
        type: Boolean,
        default: true,
    },
    isShowFlash: {
        type: Boolean,
        default: true,
    },
});

const flash = computed(() => usePage().props.flash);
const errors = computed(() => usePage().props.errors);
</script>

<template>
    <div
        v-if="flash.message && isShowFlash"
        class="alert fade show"
        :class="{
            'alert-success': flash.message.status == 'success',
            'alert-danger': flash.message.status == 'error',
            'alert-warning': flash.message.status == 'warning',
        }"
        role="alert"
    >
        {{ flash.message.message }}
    </div>

    <div
        v-if="!_.isEmpty(errors) && isShowValidation"
        class="alert alert-danger fade show"
        role="alert"
    >
        <ul class="mb-0">
            <li v-for="error in errors" :key="error" v-html="error"></li>
        </ul>
    </div>
</template>
