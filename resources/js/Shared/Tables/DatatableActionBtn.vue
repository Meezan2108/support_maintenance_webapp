<script setup>
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    icon: String,
    url: String,
    label: {
        type: String,
        default: "",
    },
    classStyle: {
        type: String,
        default: "",
    },
    method: {
        type: String,
        default: "get",
    },
});

const remove = async () => {
    const result = await Swal.fire({
        icon: "warning",
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    });

    if (!result.isConfirmed) {
        return false;
    }

    router.post(props.url, { _method: props.method });
};
</script>

<template>
    <button
        v-if="method == 'delete'"
        @click="remove"
        class="btn btn-xs"
        :class="classStyle"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        :title="label"
    >
        <i :class="icon"></i>
    </button>
    <a
        v-else-if="method == 'download'"
        :href="url"
        class="btn btn-xs"
        :class="classStyle"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        :title="label"
        download
    >
        <i :class="icon"></i>
    </a>
    <Link
        v-else
        :href="url"
        class="btn btn-xs"
        :class="classStyle"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        :title="label"
    >
        <i :class="icon"></i>
    </Link>
</template>
