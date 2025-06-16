<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.project-monitoring.extension-project._partials.s2-project-timeline', [
            "milestones" => $report->granttchart
        ])

    </body>
</html>