<script setup>
import VDevider from "@/Shared/VDevider.vue";
import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit.vue";
import VInputReadonlyWithLabel from "@/Shared/Form/VInputReadonlyWithLabel.vue";
import VSelectWithLabel from "@/Shared/Form/VSelectWithLabel.vue";

import { useProposal } from "@/Composable/useProposal.js";

import { generateArrYearQuarter } from "@/Helpers/date.js";
import { formatNumber, getIntValue } from "@/Helpers/number.js";

import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import axios from "axios";
import VProjectExpenditureTable from "./Partials/VProjectExpenditureTable.vue";
import VRadioWithLabel from "@/Shared/Form/VRadioWithLabel.vue";

const props = defineProps({
    additional: Object,
});

const { initValue, urlSubmit, method, formType, projectCostSeries } =
    props.additional;

const elTableActualExpenditure = ref(null);

const proposal = ref({});

const form = useForm({
    proposal_id: props.additional.proposal_id,
    year_quarter: initValue?.year_quarter,
    year: initValue?.year,
    quarter: initValue?.quarter,
    total_recieved: initValue?.total_recieved ?? 0,
    total_expenditure: initValue?.total_expenditure ?? 0,
    actual_project_expenditure: [],
    is_inline_plan: initValue?.is_inline_plan,
    _method: method,
});

const { getProjectCostYear, getProjectCostTotalYear } = useProposal(proposal);

const emits = defineEmits(["onNext"]);

const appBaseUrl = usePage().props.appBaseUrl;

const handleClickNext = async () => {
    if (!form.year_quarter) {
        form.errors.year_quarter = "Please Choose Project Quarter";
        return;
    }
    const [year, quarter] = form.year_quarter.split("-");

    form.year = year;
    form.quarter = quarter;

    form.post(urlSubmit, {
        preserveScroll: true,
        onSuccess: () => {
            emits("onNext", form.data());
        },
    });
};

const getProposal = async (id) => {
    const response = await axios.get(`${appBaseUrl}/resources/proposal/${id}`);
    return response;
};

const populateProposal = (id) => {
    getProposal(id).then((response) => {
        const { data } = response.data;
        proposal.value = data;
    });
};

watch(
    () => props.additional.proposal_id,
    (newValue) => {
        populateProposal(newValue);
    }
);

watch(proposal, (newValue) => {
    form.actual_project_expenditure = mapProjectCost(
        projectCostSeries,
        newValue.project_cost,
        initValue?.actual_project_expenditure ?? []
    );
});

watch(
    () => form.year_quarter,
    (newValue) => {
        const [year, quarter] = newValue.split("-");
    }
);

onMounted(() => {
    if (props.additional.proposal_id) {
        populateProposal(props.additional.proposal_id);
    }
});

const percentageExpenditure = computed(() => {
    const totalExpenditure = form.total_expenditure ?? 0;
    const totalRecieved = form.total_recieved ?? 0;

    if (!totalRecieved) return 0;

    const total = Math.round((totalExpenditure / totalRecieved) * 10000);
    return total / 100;
});

const balanceAllocation = computed(() => {
    return (
        getIntValue(form.total_recieved) - getIntValue(form.total_expenditure)
    );
});

const arrYearQuarters = computed(() => {
    let startDate = proposal.value.schedule_start_date;
    let duration = proposal.value.schedule_duration;

    let data = generateArrYearQuarter(startDate?.substr(0, 7), duration);

    if (formType == "edit") {
        return data.filter((item) => item.id == form.year_quarter);
    }

    return data;
});

const approvedCost = computed(() => {
    return formatNumber(getIntValue(proposal.value.approved_cost));
});

const mapProjectCost = (projectCostSeries, projectCost, projectDetail) => {
    return projectCostSeries.map((item) => {
        const projectCostFilter = projectCost.filter((itemFilter) => {
            return itemFilter.ref_project_cost_series_id == item.id;
        });

        const sum = projectCostFilter.reduce((accumulator, object) => {
            return getIntValue(accumulator) + getIntValue(object.cost);
        }, 0);

        const selectedItem = projectDetail.find(
            (itemFind) => itemFind.ref_project_cost_series_id == item.id
        );

        return {
            id: "",
            report_quarterly_financial_id: "",
            description: item.description,
            vseries_code: item.vseries_code,
            ref_project_cost_series_id: item.id,
            total_approved: sum,
            total_recieved: selectedItem?.total_recieved ?? 0,
            total_expenditure: selectedItem?.total_expenditure ?? 0,
        };
    });
};
</script>

<template>
    <h3>Financial Progress</h3>
    <VDevider class="my-3" />

    <div class="row">
        <div class="col-md-6">
            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="approved_cost"
                    label="Approved Project Allocation"
                    :value="approvedCost"
                    :isPlainText="false"
                    unit="RM"
                />
            </div>

            <div v-for="item in getProjectCostTotalYear" class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="year"
                    :label="'Year ' + item.year"
                    :value="formatNumber(item.total)"
                    :isPlainText="false"
                    unit="RM"
                />
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="total_allocation"
                    label="Total Allocation Received"
                    :value="formatNumber(form.total_recieved)"
                    :isPlainText="false"
                    unit="RM"
                />
            </div>

            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="total_expenditure"
                    label="Total Expenditure"
                    :value="formatNumber(form.total_expenditure)"
                    :isPlainText="false"
                    unit="RM"
                />
            </div>

            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="percentage_total_expenditure"
                    label="Percentage Total Expenditure"
                    :value="percentageExpenditure"
                    :isPlainText="false"
                    unit="%"
                />
            </div>

            <div class="col-12 mb-3">
                <VInputReadonlyWithLabel
                    elId="balance_allcations"
                    label="Balance of Allocations"
                    :value="formatNumber(balanceAllocation)"
                    :isPlainText="false"
                    unit="RM"
                />
            </div>
        </div>
    </div>

    <h5 class="mb-3 mt-3">Actual Project Expenditure</h5>
    <div class="row">
        <div class="col-md-6 mb-3 order-md-0">
            <VSelectWithLabel
                elId="yeaer_quarter"
                label="Project Quarter"
                v-model:value="form.year_quarter"
                :options="arrYearQuarters"
                :error="form.errors?.year_quarter"
                :isRequired="true"
            />
        </div>
    </div>

    <VProjectExpenditureTable
        ref="elTableActualExpenditure"
        v-model:value="form.actual_project_expenditure"
        @onChangeTotalRecieved="(value) => (form.total_recieved = value)"
        @onChangeTotalExpenditure="(value) => (form.total_expenditure = value)"
    />

    <div class="row">
        <div class="col-12 mb-3">
            <VRadioWithLabel
                elId="is_inline_plan"
                label="Is this performance in line with plan?"
                v-model:value="form.is_inline_plan"
                type="checkbox"
                :options="[
                    { id: 1, description: 'Yes' },
                    { id: 0, description: 'No' },
                ]"
                :error="form.errors.is_inline_plan"
            />
        </div>
    </div>

    <VDevider class="mb-4" />

    <div class="text-end">
        <VButtonSubmit
            type="button"
            @onCLickSubmit="handleClickNext"
            :isProcessing="form.processing"
        >
            Save
        </VButtonSubmit>
    </div>
</template>
