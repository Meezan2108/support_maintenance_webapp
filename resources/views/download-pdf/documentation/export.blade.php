<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>

        @include('download-pdf.documentation._partials.s1-project-details', ["documentation" => $documentation])

    </body>
</html>