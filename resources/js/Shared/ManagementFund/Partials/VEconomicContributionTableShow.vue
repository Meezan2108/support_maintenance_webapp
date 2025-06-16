<script setup>
import { computed, ref } from "vue";

import { clearHtmlString } from "@/Helpers/string.js";
import _ from "lodash";
import VModalObjectivesShow from "../Modals/VModalObjectivesShow.vue";
import VButtonIconShow from "@/Shared/Buttons/VButtonIconShow.vue";
import VModalEconomicContributionShow from "../Modals/VModalEconomicContributionShow.vue";

const props = defineProps({
    value: {
        type: Array,
    },
});

const economicContributions = ref(props.value);

const isShowForm = ref(false);
const initValue = ref({});
const editedIndex = ref(false);

const clickView = (index) => {
    initValue.value = economicContributions.value[index];
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
                        <h6>Economic Contribution of the Project</h6>
                    </td>
                </tr>
                <tr v-if="economicContributions.length == 0">
                    <td ccolspan="2" class="text-center">
                        <strong>No Data</strong>
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(
                        economicContribution, index
                    ) in economicContributions"
                    :key="economicContribution.id"
                >
                    <td class="form-table-action-column text-nowrap">
                        <VButtonIconShow @onClick="clickView(index)" />
                    </td>
                    <td>
                        {{
                            _.truncate(
                                clearHtmlString(
                                    economicContribution.description
                                ),
                                {
                                    length: 50,
                                    separator: /,? +/,
                                }
                            )
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <VModalEconomicContributionShow
        v-if="isShowForm"
        :value="initValue"
        @onCancel="cancelForm"
    />
</template>
