<script setup>
import _ from "lodash";

const props = defineProps({
    title: String,
    value: {
        type: Array,
    },
});
</script>

<template>
    <div class="bg-light p-2 mb-3">
        <table class="table table-borderless">
            <caption class="d-none">
                List
            </caption>
            <tbody>
                <tr>
                    <th style="width: 30px"></th>
                    <th class="fw-bold">{{ title }}</th>
                </tr>
                <tr v-for="(item, index) in value" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
