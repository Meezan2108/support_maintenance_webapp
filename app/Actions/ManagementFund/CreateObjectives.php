<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;

class CreateObjectives
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, array $arrData)
    {
        $primary = $arrData["for_primary"] ?? null;
        if (!$proposal->primaryResearchField && $primary) {
            $primary['type'] = 1;
            $proposal->primaryResearchField()->create($primary);
        } elseif ($primary) {
            $primary['type'] = 1;
            $proposal->primaryResearchField()->update($primary);
        }

        $secondary = $arrData["for_secondary"] ?? null;
        if (!$proposal->secondaryResearchField && $secondary) {
            $secondary['type'] = 2;
            $proposal->secondaryResearchField()->create($secondary);
        } elseif ($secondary) {
            $primary['type'] = 2;
            $proposal->secondaryResearchField()->update($secondary);
        }

        $objectives = $arrData["objectives"] ?? [];
        $objectivesId = [];
        foreach ($objectives as $key => $item) {
            $objective = null;
            if ($item["id"] ?? false) {
                $objective = $proposal->objectives()
                    ->where("id", $item["id"])
                    ->first();
            }

            $item["order"] = $key + 1;

            if ($objective) {
                $objective->update($item);
                $objectivesId[] = $objective->id;
            } else {
                $objective = $proposal->objectives()
                    ->create($item);
                $objectivesId[] = $objective->id;
            }
        }

        $proposal->objectives()
            ->whereNotIn('id', $objectivesId)
            ->delete();

        $proposal->update([
            'ref_research_type_id' => $arrData['ref_research_type_id'] ?? null,
            'ref_research_cluster_id' => $arrData['ref_research_cluster_id'] ?? null,
            'ref_seo_category_id' => $arrData['ref_seo_category_id'] ?? null,
            'ref_seo_group_id' => $arrData['ref_seo_group_id'] ?? null,
            'ref_seo_area_id' => $arrData['ref_seo_area_id'] ?? null,
        ]);

        return $proposal;
    }
}
