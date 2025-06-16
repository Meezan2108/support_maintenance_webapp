<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.management-fund._partials.s6-benefits', [
            "proposal" => $proposal,
            "benefitsOutput" => $benefitsOutput,
            "benefitsHuman" => $benefitsHuman,
        ])

        <div class="page-break"></div>

        @include('download-pdf.management-fund._partials.s7-research-colaboration', [
            "proposal" => $proposal,
            "organisations" => $proposal->collaborations
                ->where("type", App\Models\ProposalCollaboration::TYPE_ORGANISATIONS),
            "industries" => $proposal->collaborations
                ->where("type", App\Models\ProposalCollaboration::TYPE_INDUSTRIES),
            "teamLeader" => $proposal->teams
                ->where("type", App\Models\ProposalProjectTeam::TYPE_LEADER),
            "teamResearcher" => $proposal->teams
                ->where("type", App\Models\ProposalProjectTeam::TYPE_RESEARCHER),
            "teamStaff" => $proposal->teams
                ->where("type", App\Models\ProposalProjectTeam::TYPE_STAFF),                
        ])

        <div class="page-break"></div> 

        @include('download-pdf.management-fund._partials.s8-expenses-estimation', [
            "arrYear" => $arrYear,
            "costSeries" => $costSeries,
            "expensesEstimation" => $formatProposal["expenses_estimation"],   
        ])

        <div class="page-break"></div> 

        @include('download-pdf.management-fund._partials.s9-project-cost', [
            "arrYear" => $arrYear,
            "costSeries" => $costSeries,
            "expensesEstimation" => $formatProposal["expenses_estimation"],   
        ])
    </body>
</html>