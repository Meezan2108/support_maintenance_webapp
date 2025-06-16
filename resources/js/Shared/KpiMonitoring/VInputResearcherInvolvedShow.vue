<script setup>
import axios from "axios";
import debounce from "lodash/debounce";
import Swal from "sweetalert2";
import { watch, ref, onMounted, computed } from "vue";
import VueMultiselect from "vue-multiselect";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    value: Array,
    isRequired: {
        type: Boolean,
        default: false,
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

const projectTeams = computed({
    get() {
        return props.value;
    },
});
</script>

<template>
    <div class="row">
        <label
            :for="elId"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size mt-1 text-sm-end fw-bold mb-sm-0 mb-2 position-relative'
            "
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div :class="'col-sm-' + widthInput">
            <table class="table table-borderless">
                <caption class="d-none">
                    Table Team
                </caption>
                <tr v-for="(item, index) in projectTeams" :key="item.id">
                    <td>{{ item.name }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>
