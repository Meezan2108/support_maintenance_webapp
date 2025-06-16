<style>
    
</style>

<div id="s1-project-identification" class="line-height-3 text-bold">
    <section>
        <div class="mb-4">
            <center><h2 class="title-1">Extension of Project</h2></center>
        </div>

    </section>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <td>
                    <div class="mb-1">
                        <h5 class="title-1"><span class="number">A.</span> Project Details</h5>
                    </div>
                    <div>
                        <table class="sub-table-no-border">
                            <tr>
                                <td class="number">1.</td>
                                <td>Project Number</td>
                                <td>:</td>
                                <td>{{ $report->proposal->project_number }}</td>
                            </tr>
                            <tr>
                                <td class="number">2.</td>
                                <td>Project Title</td>
                                <td>:</td>
                                <td>{{ $report->proposal->project_title }}</td>
                            </tr>
                            <tr>
                                <td class="number">3.</td>
                                <td>Project Leader</td>
                                <td>:</td>
                                <td>{{ $report->proposal->researcher->name }}</td>
                            </tr>
                            <tr>
                                <td class="number">4.</td>
                                <td>Justification</td>
                                <td>:</td>
                                <td>{{ $report->justification }}</td>
                            </tr>
                            <tr>
                                <td class="number">5.</td>
                                <td>New Fund (if applicable)</td>
                                <td>:</td>
                                <td>{{ $report->new_fund }}</td>
                            </tr>
                            <tr>
                                <td class="number">6.</td>
                                <td>Actual Completion Date</td>
                                <td>:</td>
                                <td>{{ $actualCompletionDate->format("Y-m-d") }}</td>
                            </tr>
                            <tr>
                                <td class="number">6.</td>
                                <td>Extension Duration</td>
                                <td>:</td>
                                <td>{{ $report->duration }} Month</td>
                            </tr>
                            <tr>
                                <td class="number">7.</td>
                                <td>New End Project Date</td>
                                <td>:</td>
                                <td>{{ $actualCompletionDate->addMonths($report->duration)->format("Y-m-d") }}</td>
                            </tr>
                            <tr>
                                <td class="number">8.</td>
                                <td>Balance to date</td>
                                <td>:</td>
                                <td>RM {{ number_format($report->balance_to_date) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="mb-1">
                        <h5 class="title-1"><span class="number">B.</span> Milestone</h5>
                    </div>
                    <div>
                        <table class="table-border line-height-1" style="font-size: 14px; width:100%;">
                            <thead>
                                <tr>
                                    <th><center>Milestone</center></th>
                                    <th><center>Date</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report->proposal->milestones as $k => $milestone)
                                    <tr>
                                        <td style="font-weight: lighter;">{{ $milestone->activities }}</td>
                                        <td style="font-weight: lighter;">{{ date("Y-m-d", strtotime($milestone->from)) }}</td>
                                    </tr>
                                @endforeach
                                @foreach($otherMilestones as $k => $milestone)
                                    <tr>
                                        <td style="font-weight: lighter;">{{ $milestone->description }}</td>
                                        <td style="font-weight: lighter;">{{ date("Y-m-d", strtotime($milestone->from)) }}</td>
                                    </tr>
                                @endforeach
                                @foreach($report->granttchart as $k => $v)
                                    <tr>
                                        <td style="font-weight: lighter;">{{ $v->description }}</td>
                                        <td style="font-weight: lighter;">{{ date("Y-m-d", strtotime($v->from)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</div>