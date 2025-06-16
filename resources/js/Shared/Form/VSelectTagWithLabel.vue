<script setup>
import { watch, ref, watchEffect } from "vue";
import VueMultiselect from "vue-multiselect";

const addTag = (newTag) => {
    const tag = {
        id: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
        description: newTag,
    };

    options.value.push(tag);
    selGroup.value.push(tag);
};

const limitText = (count) => {
    return `and ${count} other's`;
};

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    label: String,
    value: Array,
    error: String,
});

const selGroup = ref([]);
const options = ref([]);

props.value.map((item) => addTag(item));

const emits = defineEmits(["update:value"]);

watchEffect(() => {
    if (selGroup.value.length > 0) {
        let data = selGroup.value.map((item) => item.description);
        emits("update:value", data);
    }
});
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            class="col-sm-3 label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative"
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div class="col-sm-9">
            <VueMultiselect
                :id="elId"
                v-model="selGroup"
                label="description"
                track-by="id"
                tag-placeholder="Add this as new tag"
                placeholder="Search or add a tag"
                open-direction="bottom"
                :limit="3"
                :limit-text="limitText"
                :multiple="true"
                :taggable="true"
                :options="options"
                @tag="addTag"
                :class="{ 'border-error': error }"
            >
            </VueMultiselect>
        </div>
    </div>

    <div v-if="error" class="row">
        <div class="col-sm-9 offset-sm-3 text-danger font-error">
            {{ error }}
        </div>
    </div>
</template>
