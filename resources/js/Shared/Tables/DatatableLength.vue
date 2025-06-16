<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    lengths: {
        type: Array,
        default: [],
    },
    activeLength: {
        type: Number,
        default: 20,
    },
});

const arrLength = ref([10, 20, 50, 100]);
const perPage = ref(props.activeLength);

if (props.lengths.length) {
    arrLength.value = props.lengths;
}

const emit = defineEmits(["onChange"]);

watch(perPage, (value) => {
    emit("onChange", value);
});
</script>

<template>
    <div class="dataTables_length">
        <label>
            Show
            <select v-model="perPage" class="form-select form-select-sm">
                <option
                    v-for="length in arrLength"
                    :key="length"
                    :value="length"
                >
                    {{ length }}
                </option>
            </select>
            entries
        </label>
    </div>
</template>
