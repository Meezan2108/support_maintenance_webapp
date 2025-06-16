<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    additional: Object,
});

const { initValue } = props.additional;

const form = useForm({
    asses_research: initValue?.asses_research,
    asses_schedule: initValue?.asses_schedule,
    asses_cost: initValue?.asses_cost,
});
</script>

<template>
    <h3>Assessment</h3>

    <VDevider class="my-3" />
    <div class="row">
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="asses_research"
                label="Assessment of Research Approach"
                subLabel="(Please highlight the main steps actually performed and indicate any major departure from the planned approach or any major difficulty encountered)"
                :value="form.asses_research"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="asses_schedule"
                label="Assessment of the Project Schedule"
                subLabel="(Please make any relevant comment regarding the actual duration of the project and highlight any significant variation from plan)"
                :value="form.asses_schedule"
            />
        </div>
        <div class="col-12 mb-3">
            <VContentEditorReadonlyWithLabel
                elId="asses_cost"
                label="Assessment of Project Costs"
                subLabel="(Please comment on the appropriateness of the original budget and highlight any major departure from the planned budget)"
                :value="form.asses_cost"
            />
        </div>
    </div>
</template>
