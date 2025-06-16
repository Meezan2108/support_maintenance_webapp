<script setup>
import axios from "axios";
import debounce from "lodash/debounce";
import Swal from "sweetalert2";
import { watch, ref, onMounted, computed } from "vue";
import VueMultiselect from "vue-multiselect";
import VButtonIconDelete from "@/Shared/Buttons/VButtonIconDelete.vue";

const props = defineProps({
    elId: {
        Type: String,
        default: "",
    },
    label: String,
    value: Array,
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

const projectTeams = computed({
    get() {
        return props.value;
    },
});

watch(
    () => props.filters,
    (newValue) => {
        asyncFind("");
    }
);

watch(selGroup, (value) => {
    if (!value) {
        return false;
    }

    const searchIndex = projectTeams.value.findIndex(
        (item) => item.user_id == value.id
    );

    if (searchIndex != -1) {
        Swal.fire({
            icon: "warning",
            title: "Data Sudah ada!",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Oke!",
        });

        selGroup.value = null;
        return false;
    }

    projectTeams.value.push({
        user_id: value.id,
        name: value.description,
    });

    emits("update:value", projectTeams.value);

    selGroup.value = null;
});

const asyncFind = debounce((query) => {
    isLoading.value = true;
    ajaxFind(query).then((response) => {
        options.value = response.data.data;
        isLoading.value = false;
    });
}, 500);

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

const clickDelete = async (index) => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Are you sure?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    emits(
        "update:value",
        projectTeams.value.filter((item, key) => index != key)
    );
};
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
                :hide-selected="true"
                @search-change="asyncFind"
                :class="{ 'border-error': error }"
            >
            </VueMultiselect>
        </div>
    </div>

    <div v-if="projectTeams.length > 0" class="row mt-2">
        <div :class="'col-sm-' + widthInput + ' offset-sm-' + widthLabel">
            <table class="table table-borderless">
                <caption class="d-none">
                    Table Team
                </caption>
                <tr v-for="(item, index) in projectTeams" :key="item.id">
                    <th style="width: 50px">
                        <VButtonIconDelete
                            classStyle="text-danger"
                            @onClick="clickDelete(index)"
                        />
                    </th>
                    <td>{{ item.name }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div v-if="error" class="row">
        <div
            :class="
                'col-sm-' +
                widthInput +
                ' offset-sm-' +
                widthLabel +
                ' text-danger font-error'
            "
        >
            {{ error }}
        </div>
    </div>
</template>
