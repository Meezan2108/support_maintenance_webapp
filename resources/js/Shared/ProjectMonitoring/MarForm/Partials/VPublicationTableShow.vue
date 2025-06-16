<script setup>
const props = defineProps({
    value: {
        type: Array,
    },
});
</script>

<template>
    <div class="bg-light p-2">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="5">
                        <h6>Publications and paper</h6>
                    </td>
                </tr>
                <tr v-for="(publication, index) in value" :key="index">
                    <td class="text-nowrap text-center" width="30px">
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ publication.title }}
                    </td>
                    <td>
                        {{ publication.publisher }}
                    </td>
                    <td>
                        {{ publication.ref_pub_type_description }}
                    </td>
                    <td width="20%">
                        {{ publication.date }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
