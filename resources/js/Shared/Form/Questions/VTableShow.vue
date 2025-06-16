<script setup>
import { ref, watch } from "vue";
import VTableRow from "./VTableRow.vue";

const props = defineProps({
    elId: String,
    label: String,
    options: Array,
    value: Array,
    error: {
        type: String,
        default: "",
    },
});

const dataValue = ref(props.value ?? []);
</script>

<template>
    <div class="">
        <label class="label-size fw-bold mb-sm-0 mb-2 form-label">
            {{ label }}
        </label>
        <div class="ms-2">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td v-for="option in options" :key="option">
                            {{ option }}
                        </td>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(row, index) in dataValue" :key="index">
                        <td v-for="(option, index2) in options" :key="option">
                            {{ row[index2] ?? "" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-if="error" class="row">
        <div class="text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
