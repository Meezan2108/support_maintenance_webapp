<html>

    <head>
        @include('download-pdf-component.style-pdf')
    </head>

    <body>
        <div class="mb-1">
            <h1 class="title-1 align-center">LKM R&D KPI REPORTING YEAR {{ $year }}</h1>
        </div>

        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.proposal', [
            "year" => $year,
            "typeOfFundings" => $proposal["typeOfFundings"],
            "proposals" => $proposal["proposals"]
        ])
        
        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.publications', [
            "year" => $year,
            "targetPeriod" => $publications["targetPeriod"],
            "pubTypes" => $publications["pubTypes"],
            "publications" => $publications["publications"]
        ])

        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.recognitions', [
            "year" => $year,
            "targetPeriod" => $recognitions["targetPeriod"],
            "recognitions" => $recognitions["recognitions"]
        ])

        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.output-rnd', [
            "year" => $year,
            "targetPeriod" => $outputRnd["targetPeriod"],
            "outputTypes" => $outputRnd["outputTypes"],
            "outputs" => $outputRnd["outputs"]
        ])
        
        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.ipr', [
            "year" => $year,
            "targetPeriod" => $ipr["targetPeriod"],
            "ipr" => $ipr["ipr"]
        ])

        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.commercialization', [
            "year" => $year,
            "targetPeriod" => $commercialization["targetPeriod"],
            "commercializations" => $commercialization["commercializations"]
        ])

        <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.asl', [
            "year" => $year,
            "targetPeriod" => $asl["targetPeriod"],
            "targetSubCategories" => $asl["targetSubCategories"]
        ])
       
       <div class="mb-3"></div>
        @include('download-pdf.kpi-monitoring.target-kpi._partials.imported-germplasm', [
            "year" => $year,
            "targetPeriod" => $importedGermplasm["targetPeriod"],
            "targetCategory" => $importedGermplasm["targetCategory"]
        ])
    </body>
</html>