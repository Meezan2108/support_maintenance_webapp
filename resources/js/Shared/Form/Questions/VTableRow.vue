<script setup>
import debounce from "lodash/debounce";
import { reactive, ref, watch } from "vue";

const props = defineProps({
    options: Array,
    index: Number,
    value: Array,
    error: {
        type: String,
        default: "",
    },
});

const emits = defineEmits(["onChange", "onDelete"]);

const dataValue = reactive(
    props.value ?? new Array(props.options.length).fill("")
);

const emitData = debounce(() => {
    emits("onChange", dataValue, props.index);
}, 300);

const remove = () => {
    emits("onDelete", props.index);
};

watch(
    () => props.value,
    (newValue) => {
        Object.assign(dataValue, newValue);
    }
);
</script>

<template>
    <tr>
        <td v-for="(option, index) in options" :key="option">
            <input
                class="form-control form-control-sm"
                v-model="dataValue[index]"
                @input="emitData"
            />
        </td>
        <td>
            <button
                @click="remove"
                class="btn btn-xs btn-outline-danger"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="delete"
            >
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
