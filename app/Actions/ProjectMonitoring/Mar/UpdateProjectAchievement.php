<?php

namespace App\Actions\ProjectMonitoring\Mar;

use App\Models\ReportMilestone;
use App\Models\ReportQuarterly;

class UpdateProjectAchievement
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(ReportQuarterly $report, array $arrData)
    {
        $reportMilestone = $report->reportMilestone;

        $reportMilestone = $this->updateIPR($reportMilestone, $arrData['ipr'] ?? []);
        $reportMilestone = $this->updatePublication($reportMilestone, $arrData['publications'] ?? []);
        $reportMilestone = $this->updateExpertise($reportMilestone, $arrData['expertise_development'] ?? []);
        $reportMilestone = $this->updatePrototype($reportMilestone, $arrData['prototype'] ?? []);
        $reportMilestone = $this->updateCommercialization($reportMilestone, $arrData['commercialization'] ?? []);

        return $report;
    }

    protected function updateIPR(ReportMilestone $report, array $arrData)
    {
        $arrId = [];
        foreach ($arrData as $key => $item) {

            $detail = $report->milestoneIpr()
                ->where("id", $item["id"] ?? false)
                ->first();

            $arrInsert = [
                "output" => $item["output"] ?? "",
                "date" => $item["date"] ?? ""
            ];

            if ($detail) {
                $detail->update($arrInsert);
                $arrId[] = $detail->id;
            } else {
                $detail = $report->milestoneIpr()
                    ->create($arrInsert);
                $arrId[] = $detail->id;
            }
        }

        $report->milestoneIpr()
            ->whereNotIn('id', $arrId)
            ->delete();

        return $report;
    }

    protected function updatePublication(ReportMilestone $report, array $arrData)
    {
        $arrId = [];
        foreach ($arrData as $key => $item) {

            $detail = $report->milestonePublication()
                ->where("id", $item["id"] ?? false)
                ->first();

            $arrInsert = [
                "title" => $item["title"] ?? "",
                "publisher" => $item["publisher"] ?? "",
                "ref_pub_type_id" => $item["ref_pub_type_id"] ?? "",
                "date" => $item["date"] ?? ""
            ];

            if ($detail) {
                $detail->update($arrInsert);
                $arrId[] = $detail->id;
            } else {
                $detail = $report->milestonePublication()
                    ->create($arrInsert);
                $arrId[] = $detail->id;
            }
        }

        $report->milestonePublication()
            ->whereNotIn('id', $arrId)
            ->delete();

        return $report;
    }

    protected function updateExpertise(ReportMilestone $report, array $arrData)
    {
        $arrId = [];
        foreach ($arrData as $key => $item) {

            $detail = $report->milestoneExpertiseDevelopment()
                ->where("id", $item["id"] ?? false)
                ->first();

            $arrInsert = [
                "output" => $item["output"] ?? "",
                "date" => $item["date"] ?? ""
            ];

            if ($detail) {
                $detail->update($arrInsert);
                $arrId[] = $detail->id;
            } else {
                $detail = $report->milestoneExpertiseDevelopment()
                    ->create($arrInsert);
                $arrId[] = $detail->id;
            }
        }

        $report->milestoneExpertiseDevelopment()
            ->whereNotIn('id', $arrId)
            ->delete();

        return $report;
    }

    protected function updatePrototype(ReportMilestone $report, array $arrData)
    {
        $arrId = [];
        foreach ($arrData as $key => $item) {

            $detail = $report->milestonePrototype()
                ->where("id", $item["id"] ?? false)
                ->first();

            $arrInsert = [
                "output" => $item["output"] ?? "",
                "date" => $item["date"] ?? ""
            ];

            if ($detail) {
                $detail->update($arrInsert);
                $arrId[] = $detail->id;
            } else {
                $detail = $report->milestonePrototype()
                    ->create($arrInsert);
                $arrId[] = $detail->id;
            }
        }

        $report->milestonePrototype()
            ->whereNotIn('id', $arrId)
            ->delete();

        return $report;
    }

    protected function updateCommercialization(ReportMilestone $report, array $arrData)
    {
        $arrId = [];
        foreach ($arrData as $key => $item) {

            $detail = $report->milestoneCommercialization()
                ->where("id", $item["id"] ?? false)
                ->first();

            $arrInsert = [
                "category" => $item["category"] ?? "",
                "name" => $item["name"] ?? "",
                "taker" => $item["taker"] ?? "",
                "date" => $item["date"] ?? ""
            ];

            if ($detail) {
                $detail->update($arrInsert);
                $arrId[] = $detail->id;
            } else {
                $detail = $report->milestoneCommercialization()
                    ->create($arrInsert);
                $arrId[] = $detail->id;
            }
        }

        $report->milestoneCommercialization()
            ->whereNotIn('id', $arrId)
            ->delete();

        return $report;
    }
}
