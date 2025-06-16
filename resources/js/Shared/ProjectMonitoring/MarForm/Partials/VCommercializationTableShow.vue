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
                        <h6>Commercialisation</h6>
                    </td>
                </tr>
                <tr v-for="(commercialization, index) in value" :key="index">
                    <td class="text-center text-nowrap" width="30px">
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ commercialization.name }}
                    </td>
                    <td>
                        {{ commercialization.taker }}
                    </td>
                    <td>
                        {{ commercialization.category_description }}
                    </td>
                    <td width="20%">
                        {{ commercialization.date }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
