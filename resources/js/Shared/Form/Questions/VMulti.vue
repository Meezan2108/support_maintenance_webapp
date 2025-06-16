<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    elId: String,
    label: String,
    subLabel: {
        type: String,
        default: "",
    },
    options: Array,
    value: Array,
    error: String,
});

const emits = defineEmits(["update:value", "onChange"]);

const dataValue = ref(props.value);

const emitSelection = () => {
    emits("update:value", dataValue.value);
    emits("onChange");
};
</script>

<template>
    <div class="">
        <label :for="elId" class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="">
            <div
                v-for="(option, index) in options"
                :key="option"
                class="form-check"
            >
                <input
                    :id="elId + index"
                    :name="elId"
                    type="checkbox"
                    class="form-check-input"
                    :class="{ 'is-invalid': error }"
                    v-model="dataValue"
                    :value="option"
                    @change="emitSelection"
                />
                <label class="form-check-label" :for="elId + index">
                    {{ option }}
                </label>
            </div>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
