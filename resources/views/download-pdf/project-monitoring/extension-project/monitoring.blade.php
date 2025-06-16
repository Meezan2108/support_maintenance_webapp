<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.project-monitoring.extension-project._partials.s1-project-details', ["report" => $report])

    </body>
</html>