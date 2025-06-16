<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.project-monitoring.eop._partials.s1-project-details', ["eop" => $eop, "report" => $report, "mapTeamType" => $mapTeamType])

    </body>
</html>