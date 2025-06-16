<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VInputReadonlyStyle2WithLabel from "@/Shared/Form/VInputReadonlyStyle2WithLabel.vue";

import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";
import VListOldFileShow from "@/Shared/ProjectMonitoring/ResearchProgress//Partials/VListOldFileShow.vue";

import VProjectTeamTableShow from "@/Shared/ProjectMonitoring/Partials/VProjectTeamTableShow.vue";
import VObjectivesTableShow from "@/Shared/ManagementFund/Partials/VObjectivesTableShow.vue";

import _ from "lodash";

defineProps({
    initValue: Object,
});
</script>

<template>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="year"
                        label="Year"
                        :value="initValue.year"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        elId="ref_report_type_id"
                        label="Type Of Report"
                        :value="initValue.report_type?.description"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="focus_area"
                        label="Focus Area"
                        :value="initValue.focus_area"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="pslkm"
                        label="PSLKM"
                        :value="initValue.pslkm?.description"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="issue"
                        label="Issue"
                        :value="initValue.issue"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="strategy"
                        label="Strategy"
                        :value="initValue.strategy"
                    />
                </div>
                <div class="col-12 mb-3 order-md-0">
                    <VInputReadonlyWithLabel
                        elId="program"
                        label="Program"
                        :value="initValue.program"
                    />
                </div>
            </div>
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="mb-4 mt-3">
        <h5>Project</h5>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="project_title"
                            label="Project Title"
                            :value="initValue.project_title"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="start_date"
                            label="Start Date"
                            :value="initValue.start_date"
                        />
                    </div>
                    <div class="col-md-6 mb-3">
                        <VInputReadonlyWithLabel
                            elId="end_date"
                            label="End Date"
                            :value="initValue.end_date"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <VProjectTeamTableShow
        :value="initValue.project_team"
        title="Project Team"
    />

    <div class="row">
        <div class="col-12 mb-3">
            <VObjectivesTableShow :value="initValue.objective" />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="mb-4">
        <h5>Progress</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <VInputReadonlyStyle2WithLabel
                    elId="date"
                    label="Date"
                    :value="initValue.date"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <VContentEditorReadonlyWithLabel
                    elId="summary"
                    label="Summary"
                    :value="initValue.summary"
                />
            </div>
            <div class="col-12 mb-3">
                <VListOldFileShow :value="initValue.old_files" />
            </div>
        </div>
    </div>
</template>
