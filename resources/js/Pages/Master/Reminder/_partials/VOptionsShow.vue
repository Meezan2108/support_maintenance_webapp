<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import { computed, reactive, toRaw, watch } from "vue";

const props = defineProps({
    value: Array,
    options: Array,
});

const emit = defineEmits(["update:value"]);

const form = reactive({
    hour: props.value.hour,
    day: props.value.day,
    month: props.value.month,
});

const listAvailableOption = computed(() => {
    return props.options.reverse().map((item) => {
        const temp = item.split(":");
        return {
            key: temp[0],
            max: temp[1] ?? null,
        };
    });
});

const resetForm = () => {
    form.hour = null;
    form.day = null;
    form.month = null;
};

watch(form, (newValue) => {
    emit("update:value", toRaw(form));
});
</script>

<template>
    <div v-for="item in listAvailableOption" class="row">
        <div class="col-lg-6 mb-3">
            <VInputReadonlyWithLabel
                :elId="item.key"
                :label="item.key + '(s)'"
                :value="form[item.key]"
            />
        </div>
    </div>
</template>
