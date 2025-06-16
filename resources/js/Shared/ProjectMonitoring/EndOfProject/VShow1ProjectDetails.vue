<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VButton from "@/Shared/Buttons/VButton.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import VModalShowProjectTeam from "@/Shared/ProjectMonitoring/ResearchProgress/VModalShowProjectTeam.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";
import _ from "lodash";

const props = defineProps({
    additional: Object,
});

const { initValue, projectNumbers } = props.additional;

const { proposal } = initValue;

const isShowProjectTeam = ref(false);

const clickShowProjectTeam = (value) => {
    isShowProjectTeam.value = true;
};

const closeModalProjectTeam = () => {
    isShowProjectTeam.value = false;
};
</script>

<template>
    <h3>Project Details</h3>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="proposal_id"
                        label="Project Number"
                        v-model:value="proposal.project_number"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Project Title"
                        :value="proposal?.project_title"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="researcher_name"
                        label="Project Leader"
                        :value="proposal?.researcher?.name"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3 align-items-center">
                    <div class="row align-items-sm-center">
                        <label
                            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2"
                        >
                            Project Team
                        </label>
                        <div class="col-sm-9 custom-position-relative">
                            <VButton
                                @onClick="clickShowProjectTeam"
                                :isDisabled="_.isEmpty(proposal)"
                            >
                                Show Project Team
                            </VButton>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="duration"
                        label="Project Duration"
                        :value="proposal.schedule_duration + ' Months'"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="budget_approved"
                        label="Budget Approved"
                        :value="proposal.approved_cost + ' RM'"
                    />
                </div>
            </div>
        </div>
    </div>

    <VDevider class="mb-4" />

    <VModalShowProjectTeam
        v-if="isShowProjectTeam"
        :value="proposal.teams"
        @onCancel="closeModalProjectTeam"
    />
</template>
