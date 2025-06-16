<script setup>
defineProps({
    title: String,
    arrYear: Array,
    activities: Array,
    addActivities: Array,
});

const isInRange = (start, end, year, month) => {
    var dateToCheck = new Date(
        year + "-" + String(month).padStart(2, "0") + "-01"
    );
    var startDate = new Date(start + "-01");
    var endDate = new Date(end + "-01");

    return dateToCheck >= startDate && dateToCheck <= endDate;
};
</script>

<template>
    <div class="bg-light p-2">
        <div class="table-responsive">
            <table class="table table-table-borderless">
                <tbody>
                    <tr>
                        <td
                            class="fw-bold align-bottom fixed-column bg-light"
                            rowspan="2"
                        >
                            {{ title }}
                        </td>
                        <td
                            v-for="year in arrYear"
                            :key="year"
                            colspan="12"
                            class="text-center fw-bold"
                        >
                            {{ year }}
                        </td>
                    </tr>
                    <tr>
                        <template v-for="year in arrYear" :key="year">
                            <td
                                v-for="index in 12"
                                :key="index"
                                class="text-center fw-bold"
                            >
                                {{ index }}
                            </td>
                        </template>
                    </tr>
                    <tr v-for="item in activities" :key="item.id">
                        <td class="text-nowrap fixed-column bg-light">
                            {{ item.activities }}
                        </td>
                        <template v-for="year in arrYear" :key="year">
                            <td
                                v-for="index in 12"
                                :key="index"
                                :class="{
                                    'bg-mustard': isInRange(
                                        item.from,
                                        item.to,
                                        year,
                                        index
                                    ),
                                }"
                            >
                                &nbsp;
                            </td>
                        </template>
                    </tr>
                    <tr v-for="item in addActivities" :key="item.id">
                        <td class="text-nowrap fixed-column bg-light">
                            {{ item.activities }}
                        </td>
                        <template v-for="year in arrYear" :key="year">
                            <td
                                v-for="index in 12"
                                :key="index"
                                :class="{
                                    'bg-danger': isInRange(
                                        item.from,
                                        item.to,
                                        year,
                                        index
                                    ),
                                }"
                            >
                                &nbsp;
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.bg-mustard {
    background: #ffdb58;
}
</style>
