import { computed, unref } from "vue";
import { getIntValue } from "@/Helpers/number";

export function useProposal(proposalData) {
    const getProjectCostYear = computed(() => {
        const proposal = unref(proposalData);
        if (!proposal) return [];

        const projectCost = proposal.project_cost;
        if (!projectCost?.length) {
            return [];
        }

        return projectCost[0].detail.map(item => item.year);
    });

    const getProjectCostTotalYear = computed(() => {
        const proposal = unref(proposalData);
        if (!proposal) return [];

        const projectCost = proposal.project_cost;
        if (!projectCost?.length) {
            return [];
        }

        let years = [];
        projectCost.map(item => {

            item.detail.map(itemDetail => {
                const yearIndex = years.findIndex(item => item.year == itemDetail.year);
                if (yearIndex == -1) {
                    years.push({
                        year: itemDetail.year,
                        total: getIntValue(itemDetail.cost)
                    })
                } else {
                    years[yearIndex].total += getIntValue(itemDetail.cost);
                }
            })

        });

        return years;
    });

    return {
        getProjectCostYear,
        getProjectCostTotalYear
    };
}