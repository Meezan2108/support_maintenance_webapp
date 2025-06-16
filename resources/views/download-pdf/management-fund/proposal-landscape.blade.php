<html>

    <head>
        <style>
            .page-break {
                page-break-after: always;
            }

            .align-center {
                text-align: center !important;
            }

            .align-left {
                text-align: left !important;
            }

            .align-right {
                text-align: right !important;
            }

            .mt-1 {
                margin-top: 10pt;
            }

            .mb-1 {
                margin-bottom: 10pt;
            }

            .m-1 {
                margin: 10pt;
            }


            .mt-2 {
                margin-top: 20pt;
            }

            .mb-2 {
                margin-bottom: 20pt;
            }

            .m-2 {
                margin: 20pt;
            }

            .mt-3 {
                margin-top: 30pt;
            }

            .mb-3 {
                margin-bottom: 30pt;
            }

            .m-3 {
                margin: 30pt;
            }

            .pb-3 {
                padding-bottom: 30pt;
            }

            .pb-2 {
                padding-bottom: 20pt;
            }

            .pb-1 {
                padding-bottom: 10pt;
            }

            .border {
                border: 2px solid #333;
            }

            .table-noborder {
                width: 100%;
            }
            .table-noborder td {
                width: 100%;
                vertical-align: top;
            }

            .table-noborder th {
                text-align: left;
                padding-bottom: 10pt;
            }

            .table-noborder .space{
                line-height: 20pt;
            }

            .table-noborder td.number {
                width: 30pt;
            }

            .table-noborder td.title {
                width: 125pt;
            }

            .table-noborder td.colon {
                width: 5pt;
            }

            .line-height-1 {
                line-height: 10pt;
            }

            .line-height-2 {
                line-height: 20pt;
            }

            .line-height-3 {
                line-height: 30pt;
            }

            .text-bold {
                font-weight: bold;
            }

            .table-noborder .sub-table {
                width: 100%;
            }

            .table-noborder .sub-table td.title {
                width: 92pt;
            }

            .table-border {
                border-collapse: collapse;
            }

            .table-border td, .table-border th {
                border: 1px solid #444;
                margin: 0px;
                padding: 2pt 10pt;
            }

            .table-border th {
                background: #eee;
            }
        </style>
    </head>

    <body>
 
        @include('download-pdf.management-fund._partials.s5-project-schedule', [
            "arrYear" => $arrYear,
            "milestones" => $proposal->milestones,
            "activities" => $proposal->activities
        ])

 
    </body>
</html>