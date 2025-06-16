<script setup>
import { computed, ref } from "vue";

import _ from "lodash";
import VModalOutput from "../Modals/VModalOutput.vue";
import VButtonIconEdit from "@/Shared/Buttons/VButtonIconEdit.vue";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="3">
                        <h6>{{ title }}</h6>
                    </td>
                </tr>
                <tr v-for="(output, index) in value" :key="index">
                    <td class="text-center text-nowrap" width="30px">
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ output.output }}
                    </td>
                    <td width="20%">
                        {{ output.date }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
