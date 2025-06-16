<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.project-monitoring.mar._partials.s1-project-identification', ["mar" => $mar, "report" => $report, "milestones" => $milestones])

    </body>
</html>