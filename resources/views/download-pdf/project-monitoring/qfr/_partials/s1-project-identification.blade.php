<style>
    
</style>

<div id="s1-project-identification">
    <section>
        <div class="mb-4">
            <h2 class="title-1">
                {{ $qfr->proposal->proposal_type == 1 ? 'TRF' : 'EXTERNAL FUND' }}
                QUARTERLY FINANCIAL REPORT
            </h2>
        </div>

    </section>
    <section>
        <div class="mb-2">
            Report Peroid : {{$qfr->year}}/{{$qfr->quarter}}
        </div>
    </section>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <td>
                    <div class="mb-1">
                        <h5 class="title-1"><span class="number">A.</span> PROJECT DETAILS</h5>
                    </div>
                    <div>
                        <table class="sub-table-no-border">
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">Project Number</td>
                                <td style="width: 75%">: {{ $qfr->proposal->project_number }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">PTJ Code</td>
                                <td style="width: 75%">: {{ implode(", ",$qfr->proposal->ptj_code ?? []) }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">Project Leader</td>
                                <td style="width: 75%">: {{ $qfr->proposal->researcher->name }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">Project Duration</td>
                                <td style="width: 75%">: {{ $qfr->proposal->schedule_duration }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">Project Start Date</td>
                                <td style="width: 75%">: {{ $qfr->proposal->schedule_start_date->format("Y-m") }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding-left:20px;" colspan="2">Project End Date</td>
                                <td style="width: 75%">: {{ $qfr->proposal->schedule_start_date->addMonths($qfr->proposal->schedule_duration)->format("Y-m") }}</td>
                            </tr>
                            <tr>
                                <td style="width:30%; padding-left:20px;">Tel : {{ $qfr->proposal->researcher->tel_no }} </td>
                                <td style="width:30%; padding-left:40px;">Fax : {{ $qfr->proposal->researcher->fax_no }} </td>
                                <td style="width:30%; padding-left:50px;">Email : {{ $qfr->proposal->researcher->email }} </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="mb-1">
                        <h5 class="title-1"><span class="number">B.</span> FINANCIAL PROGRESS</h5>
                    </div>
                    <div>
                        <table class="sub-table-no-border">
                            <tr>
                                <td>i.</td>
                                <td>Approved Project Allocation</td>
                                <td>:</td>
                                <td>RM {{ number_format($qfr->proposal->approved_cost) }}</td>
                            </tr>
                            @foreach($projectCostYear as $k => $v)
                                <tr>
                                    <td></td>
                                    <td style="font-weight:lighter;">Year {{ $v['year'] }}</td>
                                    <td style="font-weight:lighter;">:</td>
                                    <td style="font-weight:lighter;">RM {{ number_format($v['total']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>ii.</td>
                                <td>Total Allocation Received</td>
                                <td>:</td>
                                <td>RM {{ number_format($qfr->reportQuarterlyFinancial->total_recieved) }}</td>
                            </tr>
                            <tr>
                                <td>iii.</td>
                                <td>Total Expenditure</td>
                                <td>:</td>
                                <td>RM {{ number_format($qfr->reportQuarterlyFinancial->total_expenditure) }}</td>
                            </tr>
                            <tr>
                                <td>iv.</td>
                                <td>Percentage Total Expenditure</td>
                                <td>:</td>
                                <td>{{ round($qfr->reportQuarterlyFinancial->total_expenditure/$qfr->reportQuarterlyFinancial->total_recieved*100, 2) }} % <span>(Total Expenditure / Total Allocation Received X 100)</span></td>
                            </tr>
                            <tr>
                                <td>v.</td>
                                <td>Balance of Allocation</td>
                                <td>:</td>
                                <td>RM {{ number_format($qfr->reportQuarterlyFinancial->total_recieved - $qfr->reportQuarterlyFinancial->total_expenditure) }} <span>(Total Allocation Received – Total Expenditure)</span></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="">
                        <h5 class="title-1">Actual Project Expenditure</h5>
                    </div>
                    <div class="">
                        <h5 class="title-1">Year: {{ $report['financial_progress']['year'] }}        Quarter: {{ $report['financial_progress']['quarter'] }}</h5>
                    </div>
                    <div>
                        <table class="table-border" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th><center>Project Cost Components</center></th>
                                    <th><center>Total Approved Budget (RM)</center></th>
                                    <th><center>Total Allocation Received (RM)</center></th>
                                    <th><center>Total Cummulative Expenditure (RM)</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projectCostSeries as $k => $v)
                                    <tr>
                                        <td style="font-weight: lighter;">{{ $v->description }} ({{ $v->vseries_code }})</td>
                                        <td style="font-weight: lighter; text-align: right;">{{ number_format($v->detail['total_approved']) }}</td>
                                        <td style="font-weight: lighter; text-align: right;">{{ number_format($v->detail['total_recieved']) }}</td>
                                        <td style="font-weight: lighter; text-align: right;">{{ number_format($v->detail['total_expenditure']) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                        <td><center>Total</center></td>
                                        <td style="text-align: right;">{{ number_format($totalProjectCostSeries['approved']) }}</td>
                                        <td style="text-align: right;">{{ number_format($totalProjectCostSeries['received']) }}</td>
                                        <td style="text-align: right;">{{ number_format($totalProjectCostSeries['expenditure']) }}</td>
                                    </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="line-height-3">
                        <div style="display:inline-block; font-size:12px; font-weight:lighter;">Is this performance in line with plan?</div>
                        <div style="display:inline-block; padding-bottom:8px;">
                            <div style="width:10px;height:10px;border:1px solid #000; font-size:10px; @if($report['financial_progress']['is_inline_plan'] == '1') background-color:#000; @endif"></div>
                        </div>
                        <div style="display:inline-block; font-size:12px; font-weight:lighter;">Yes</div>
                        <div style="display:inline-block;padding-bottom:8px;">
                            <div style="width:10px;height:10px;border:1px solid #000;@if($report['financial_progress']['is_inline_plan'] == '0') background-color:#000; @endif"></div>
                        </div>
                        <div style="display:inline-block; font-size:12px; font-weight:lighter;">No</div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="border p-1">
            <table class="table-noborder" width="100%">
                <tr>
                    <td>
                        <div class="line-height-1">
                            <h5 class="title-1"><span class="number">C.</span> Reasons for variations from budget</h5>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="text-justified">
                {!! $report['budget_variations']['reasons'] !!}
            </div>
        </div>
        <div class="border p-1">
            <table class="table-noborder" width="100%">
                <tr>
                    <td>
                        <div class="line-height-1">
                            <h5 class="title-1"><span class="number">D.</span> Proposed corrective action</h5>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="text-justified">
                {!! $report['proposed_action']['proposed_action'] !!}
            </div>
        </div>
        <div class="border p-1">
            <table class="table-noborder" width="100%">
                <tr>
                    <td>
                        <div style="height: 100px;">
                            <table class="sub-table-no-border">
                                <tr>
                                    <td style="width: 400px;">Date:</td>
                                    <td>Signature:</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </section>
</div>