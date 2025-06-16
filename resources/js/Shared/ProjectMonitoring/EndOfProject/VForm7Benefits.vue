<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VBenefitGroup from "@/Shared/ProjectMonitoring/EndOfProject/Partials/VBenefitGroup.vue";

import { useForm } from "@inertiajs/vue3";
import _ from "lodash";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, questionsBenefit } = props.additional;

const form = useForm({
    answers: _.isEmpty(initValue?.answers) ? {} : initValue?.answers,
    _method: method,
});

const emits = defineEmits(["onNext"]);

const handleClickNext = async () => {
    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            emits("onNext", form.data());
        },
    });
};

const onChange = (answers) => {
    for (let [key, value] of Object.entries(answers)) {
        form.answers[key] = value;
    }
};
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
            <VBenefitGroup
                v-for="group in questionsBenefit"
                :key="group.id"
                :questionGroup="group"
                :answers="form.answers"
                @onChange="onChange"
            />
        </div>
    </div>

    <VDevider class="mb-4" />
    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Submit
        </VButtonSubmit>
    </div>
</template>
