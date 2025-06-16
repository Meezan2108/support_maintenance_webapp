<script setup>
import { ref } from "vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    label: String,
    error: String,
    value: String,
});

const inputValue = ref("");
const showPassword = ref(false);

const tooglePassword = function () {
    showPassword.value = !showPassword.value;
};

defineEmits(["update:value"]);
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative"
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div class="col-sm-9">
            <div class="d-flex align-items-center">
                <input
                    :id="elId"
                    :type="showPassword ? 'text' : 'password'"
                    class="form-control"
                    @input="$emit('update:value', $event.target.value)"
                    :class="{ 'is-invalid': error }"
                    :value="value"
                />
                <i
                    class="fas fa-eye toogle-password"
                    :class="{
                        'fa-eye': showPassword,
                        'fa-eye-slash': !showPassword,
                    }"
                    @click="tooglePassword"
                ></i>
            </div>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
<style>
.toogle-password {
    margin-left: -30px;
    cursor: pointer;
    color: #929aac;
}
</style>
