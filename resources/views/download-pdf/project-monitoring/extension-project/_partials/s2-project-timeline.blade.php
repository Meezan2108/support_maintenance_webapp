

<div id="s2-project-schedule">

    <section>

        @include('download-pdf.project-monitoring.extension-project._partials.timeline', [
            "title" => "Milestone",
            "arrYear" => $arrYear,
            "otherMilestones" => $report->proposal->milestones->map(function($item) {
                $item->from = $item->from->format("Y-m") . "-01";
                $item->to = $item->from->format("Y-m") . "-01";
                return $item;
            })
            ->merge($otherMilestones->map(function($item) {
                $item->from = $item->from->format("Y-m") . "-01";
                $item->to = $item->from->format("Y-m") . "-01";
                $item->activities = $item->description;
                return $item;
            })),
            "activities" => $milestones->map(function($item) {
                $item->from = $item->from->format("Y-m") . "-01";
                $item->to = $item->from->format("Y-m") . "-01";
                $item->activities = $item->description;
                return $item;
            })
        ])
    </section>
</div>