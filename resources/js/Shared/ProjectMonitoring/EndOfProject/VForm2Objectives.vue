<script setup>
import VDevider from "@/Shared/VDevider.vue";

import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import { computed } from "vue";

const props = defineProps({
    additional: Object,
});

const initValue = computed(() => props.additional.initValue);

const emits = defineEmits(["onNext"]);

const handleClickNext = async () => {
    emits("onNext", initValue.value);
};
</script>
<template>
    <h3>Objective of the Project</h3>
    <VDevider class="my-3" />

    <h4>Socio-economic Objectives (SEO)</h4>
    <small class="fst-italic">
        Which socio-economic objectives are addressed by the project? (Please
        identify the Research Priority Area , SEO Category and SEO Group under
        which the project falls. Refer to the Malaysian Research & Development
        Classification System (MRDCS) – 6th edition)
    </small>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="Research Priority Area"
                        :value="initValue?.seo_area?.description"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="SEO Category"
                        :value="initValue?.seo_category?.description"
                        :isPlainText="false"
                    />
                </div>
                <div class="col-12 mb-3">
                    <VInputReadonlyWithLabel
                        label="SEO Group"
                        :value="initValue?.seo_group?.description"
                        :isPlainText="false"
                    />
                </div>
            </div>
        </div>
    </div>

    <h4>Field of Research</h4>
    <small class="fst-italic">
        Which are the two main FOR Categories, FOR Groups, and FOR Areas of your
        project? (Please refer to the Malaysian Research & Development
        Classification System (MRDCS) – 6th edition)
    </small>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0"></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <h5>Primary Field of Research</h5>
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-0">
            <h5>Secondary Field of Research</h5>
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputReadonlyWithLabel
                label="FOR Category"
                :value="
                    initValue?.primary_research_field?.category?.description
                "
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="FOR Category"
                :value="
                    initValue?.secondary_research_field?.category?.description
                "
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-2">
            <VInputReadonlyWithLabel
                label="FOR Group"
                :value="initValue?.primary_research_field?.group?.description"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-2">
            <VInputReadonlyWithLabel
                label="FOR Group"
                :value="initValue?.secondary_research_field?.group?.description"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-0 order-md-3">
            <VInputReadonlyWithLabel
                label="FOR Area"
                :value="initValue?.primary_research_field?.area?.description"
                :isPlainText="false"
            />
        </div>

        <div class="col-md-6 mb-3 order-1 order-md-3">
            <VInputReadonlyWithLabel
                label="FOR Area"
                :value="initValue?.secondary_research_field?.area?.description"
                :isPlainText="false"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButtonSubmit type="button" @onCLickSubmit="handleClickNext">
            Save
        </VButtonSubmit>
    </div>
</template>
