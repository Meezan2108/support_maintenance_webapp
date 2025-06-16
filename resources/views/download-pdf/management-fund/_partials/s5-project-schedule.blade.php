

<div id="s5-project-schedule">
    <section  class="line-height-3">
        <div class="mb-1">
            <h2 class="title-1  text-bold">
                <span class="number">D.</span>
                Project Schedule 
            </h2>
        </div>
    </section>

    <section>
        @include('download-pdf-component.timeline', [
            "title" => "Activities",
            "arrYear" => $arrYear,
            "activities" => $activities
        ])

        @if(count($arrYear) > 3)
        <div class="page-break"></div> 
        @else
        <div class="mb-3"></div> 
        @endif

        @include('download-pdf-component.timeline', [
            "title" => "Milestone",
            "arrYear" => $arrYear,
            "activities" => $milestones->map(function($item) {
                $item->from = $item->from->format("Y-m") . "-01";
                $item->to = $item->from->format("Y-m") . "-01";
                return $item;
            })
        ])
    </section>
</div>