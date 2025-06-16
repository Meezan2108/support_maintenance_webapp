<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputWithLabel from "@/Shared/Form/VInputWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import { router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const props = defineProps({
    user: Object,
    value: String,
});

const emits = defineEmits(["update:value"]);
</script>

<template>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Name of Evaluator"
                :value="user?.name"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputReadonlyWithLabel
                label="Position"
                :value="user?.position?.description"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Division"
                :value="user?.division?.description"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="Date Of Evaluation"
                :value="value"
                :isPlainText="false"
            />
        </div>
    </div>
</template>
