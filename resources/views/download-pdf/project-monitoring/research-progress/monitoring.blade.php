<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.project-monitoring.research-progress._partials.s1-project-details', ["report" => $report, "mapTeamType" => $mapTeamType])
        <div class="page-break"></div>
        @include('download-pdf.project-monitoring.research-progress._partials.s2-progress', ["report" => $report, "mapTeamType" => $mapTeamType])

    </body>
</html>