<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VContentEditorReadonlyWithLabel from "@/Shared/Form/VContentEditorReadonlyWithLabel.vue";

import { useForm } from "@inertiajs/vue3";
import VBenefitGroupShow from "./Partials/VBenefitGroupShow.vue";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, questionsBenefit } = props.additional;
</script>

<template>
    <h3>Benefits of the Project</h3>
    <p style="font-style: italic">
        (Please identify the actual benefits arising from the project as defined
        in Section III of the Application Form. For examples of outputs,
        organisational outcomes and sectoral/national impacts, please refer to
        Section III of the Guidelines for the Application of R&D Funding under
        ScienceFund)
    </p>
    <VDevider class="my-3" />
    <div class="row">
        <div class="col-12 mb-3">
            <VBenefitGroupShow
                v-for="group in questionsBenefit"
                :key="group.id"
                :questionGroup="group"
                :answers="initValue?.answers"
            />
        </div>
    </div>
</template>
