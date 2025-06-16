<script setup>
import axios from "axios";
import debounce from "lodash/debounce";
import { watch, ref, onMounted } from "vue";
import VueMultiselect from "vue-multiselect";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    value: Number | String,
    error: String,
    url: String,
    searchBy: {
        type: String,
        default: "description",
    },
    filters: {
        type: Object,
        default: null,
    },
    isRequired: {
        type: Boolean,
        default: false,
    },
    widthLabel: {
        type: Number,
        default: 3,
    },
    widthInput: {
        type: Number,
        default: 9,
    },
});

const selGroup = ref(null);
const isLoading = ref(false);
const options = ref([]);

const emits = defineEmits(["update:value"]);

onMounted(() => {
    if (props.value) {
        asyncPopulate(props.value);
    }

    asyncFind("");
});

watch(
    () => props.value,
    (newValue) => {
        if (props.value) {
            asyncPopulate(newValue);
        }
    }
);

watch(
    () => props.filters,
    (newValue) => {
        asyncFind("");
    }
);

watch(selGroup, () => {
    if (selGroup.value) {
        emits("update:value", selGroup.value.id);
    }
});

const asyncFind = debounce((query) => {
    isLoading.value = true;
    ajaxFind(query).then((response) => {
        options.value = response.data.data;
        isLoading.value = false;
    });
}, 500);

const asyncPopulate = (id) => {
    isLoading.value = true;
    ajaxShow(id).then((response) => {
        selGroup.value = response.data.data;
        isLoading.value = false;
    });
};

const clearAll = () => {
    selGroup.value = [];
};

const ajaxFind = async (query) => {
    const response = await axios.get(props.url, {
        params: {
            search: query,
            search_by: props.searchBy,
            ...props.filters,
        },
    });

    return response;
};

const ajaxShow = async (id) => {
    const response = await axios.get(props.url + `/${id}`, {
        params: {
            show_all: 1,
        },
    });
    return response;
};

const getSelGroup = () => {
    return selGroup.value;
};

defineExpose({ getSelGroup });
</script>

<template>
    <div class="row align-items-sm-center">
        <label
            :for="elId"
            :class="
                'col-sm-' +
                widthLabel +
                ' label-size text-sm-end fw-bold mb-sm-0 mb-2 position-relative'
            "
        >
            {{ label }}
            <span v-if="isRequired" class="is-required">*</span>
        </label>
        <div :class="'col-sm-' + widthInput">
            <VueMultiselect
                :id="elId"
                v-model="selGroup"
                label="description"
                track-by="id"
                placeholder="Type to search"
                open-direction="bottom"
                :options="options"
                :multiple="false"
                :searchable="true"
                :loading="isLoading"
                :internal-search="false"
                :clear-on-select="false"
                :options-limit="20"
                :max-height="600"
                :show-no-results="true"
                :hide-selected="false"
                @search-change="asyncFind"
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
