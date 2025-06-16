<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>
 
        @include('download-pdf.management-fund._partials.cover', ["proposal" => $proposal])

        <div class="page-break"></div>

        @include('download-pdf.management-fund._partials.s1-project-identification', ["proposal" => $proposal])
        
        <div class="page-break"></div>

        @include('download-pdf.management-fund._partials.s2-objectives', ["proposal" => $proposal])

        <div class="page-break"></div>

        @include('download-pdf.management-fund._partials.s3-research-background', ["proposal" => $proposal])

        <div class="page-break"></div>

        @include('download-pdf.management-fund._partials.s4-research-approach', ["proposal" => $proposal])

    </body>
</html>