<script setup>
import { computed, ref } from "vue";

import { clearHtmlString } from "@/Helpers/string.js";
import _ from "lodash";
import VModalObjectivesShow from "../Modals/VModalObjectivesShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";

const props = defineProps({
    value: {
        type: Array,
    },
    label: String,
    subLabel: String,
});

const objectives = ref(props.value);

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const clickView = (index) => {
    initValue.value = objectives.value[index];
    editedIndex.value = index;
    isShowForm.value = true;
};

const cancelForm = () => {
    initValue.value = {};
    isShowForm.value = false;
    editedIndex.value = false;
};
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2">
                        <h6>{{ label }}</h6>
                        <div v-if="subLabel">
                            <small class="fst-italic">{{ subLabel }}</small>
                        </div>
                    </td>
                </tr>
                <tr v-if="objectives.length == 0">
                    <td ccolspan="2" class="text-center">
                        <strong>No Data</strong>
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(objective, index) in objectives"
                    :key="objective.id"
                >
                    <td class="form-table-action-column text-nowrap">
                        <VButtonIconShow @onClick="clickView(index)" />
                    </td>
                    <td>
                        {{
                            _.truncate(clearHtmlString(objective.description), {
                                length: 50,
                                separator: /,? +/,
                            })
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalObjectivesShow
        v-if="isShowForm"
        :value="initValue"
        @onCancel="cancelForm"
    />
</template>
