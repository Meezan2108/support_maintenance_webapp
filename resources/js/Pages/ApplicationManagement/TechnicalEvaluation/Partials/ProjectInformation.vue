<script setup>
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";

defineProps({
    proposal: Object,
});
</script>

<template>
    <div class="row">
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Poject Title"
                :value="proposal.project_title"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-1">
            <VInputReadonlyWithLabel
                label="Poject Leader"
                :value="proposal.researcher.name"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-0 order-md-0">
            <VInputReadonlyWithLabel
                label="Telephone"
                :value="proposal.researcher.tel_no"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 order-1 order-md-1">
            <VInputReadonlyWithLabel
                label="Fax"
                :value="proposal.researcher.fax_no"
                :isPlainText="false"
            />
        </div>
        <div class="col-md-6 mb-3 offset-md-6 order-2 order-md-2">
            <VInputReadonlyWithLabel
                label="Email"
                :value="proposal.researcher.email"
                :isPlainText="false"
            />
        </div>
    </div>
</template>
