<div id="s1-project-identification">
    <section>
        <div class="mb-4">
            <center><h2 class="title-1">Research Progress</h2></center>
        </div>

    </section>

    <section>
        <div class="border p-1">
            <table class="table-noborder line-height-2" width="100%">
                <tr>
                    <td class="number">1.</td>
                    <td class="title">Year</td>
                    <td class="colon">:</td>
                    <td>{{ $report->year }}</td>
                </tr>
                <tr>
                    <td class="number">2.</td>
                    <td>Type Of Report</td>
                    <td>:</td>
                    <td>{{ $report->reportType->description }}</td>
                </tr>
                <tr>
                    <td class="number">3.</td>
                    <td>Focus Area</td>
                    <td>:</td>
                    <td>{{ $report->focus_area }}</td>
                </tr>
                <tr>
                    <td class="number">4.</td>
                    <td>Issue</td>
                    <td>:</td>
                    <td>{{ $report->issue }}</td>
                </tr>
                <tr>
                    <td class="number">5.</td>
                    <td>Strategy</td>
                    <td>:</td>
                    <td>{{ $report->strategy }}</td>
                </tr>
                <tr>
                    <td class="number">6.</td>
                    <td>Program</td>
                    <td>:</td>
                    <td>{{ $report->program }}</td>
                </tr>
                <tr>
                    <td colspan="4"><h4>Project</h4></td>
                </tr>
                <tr>
                    <td class="number">1.</td>
                    <td>Project Title</td>
                    <td>:</td>
                    <td>{{ $report->project_title }}</td>
                </tr>
                <tr>
                    <td class="number">2.</td>
                    <td>Start Date</td>
                    <td>:</td>
                    <td>{{ optional($report->start_date)->format("Y-m") }}</td>
                </tr>
                <tr>
                    <td class="number">3.</td>
                    <td>End Date</td>
                    <td>:</td>
                    <td>{{ optional($report->end_date)->format("Y-m") }}</td>
                </tr>

                <tr>
                    <td class="number">4.</td>
                    <td>Project Team</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
            <div class="margin-tab1 mt-1 mb-1">
                <table class="table-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report->projectTeam as $k => $v)
                            <tr>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $v->name }}</td>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $mapTeamType[$v->type] ?? "Staff" }}</td>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $v->organization }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <table class="table-noborder line-height-2" width="100%"><tr>
                <td class="number">5.</td>
                <td class="title">Objectives</td>
                <td class="colon">:</td>
                <td></td>
                </tr>
            </table>
            @foreach($report->objective as $objective)
                <div class="text-justified margin-tab1">
                {!! $objective->description !!}
                </div>
            @endforeach
        </div>
    </section>
</div>