<style>
    
</style>

<div id="s1-project-identification">
    <section>
        <div class="mb-1">
            <h2 class="title-1  text-bold"><span class="number">D.</span> Research Approach</h2>
        </div>

    </section>
    <section>
        <table class="table-noborder line-height-1">
            <tr class="text-bold">
                <td class="number">1.</td>
                <td >Research Methodology</td>
            </tr>
        </table>
        <div class="text-justified margin-tab1">
            {!! $proposal->research_methodology !!}
        </div>
        
        <table class="table-noborder line-height-1">
            <tr class="text-bold">
                <td class="number">2.</td>
                <td >Project Activities</td>
            </tr>
        </table>
            
        <div class=" margin-tab1  mb-3">
            <table class="table-border mt-1" width="100%">
                <tr>
                    <th>Activities</th>
                    <th width="90pt">From</th>
                    <th width="90pt">To</th>
                </tr>
                @foreach($proposal->activities as $activitie)
                    <tr>
                        <td width="">{{ $activitie->activities }}</td>
                        <td>{{ $activitie->from->format("M Y") }}</td>
                        <td>{{ $activitie->to->format("M. Y") }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <table class="table-noborder line-height-1">
            <tr class="text-bold">
                <td class="number">3.</td>
                <td >Project Milestones</td>
            </tr>
        </table>
        <div class=" margin-tab1  mb-3">
            <table class="table-border mt-1" width="100%">
                <tr>
                    <th>Milestones</th>
                    <th width="90pt">Date</th>
                </tr>
                @foreach($proposal->milestones as $milestone)
                    <tr>
                        <td>{{ $milestone->activities }}</td>
                        <td>{{ $milestone->from->format("d M. Y") }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <table class="table-noborder line-height-1">
            <tr class="text-bold">
                <td class="number">4.</td>
                <td >Risk of the project</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2">
                    <table class="sub-table mt-1 line-height-2">
                        <tr >
                            <td class="number text-bold">i.</td>
                            <td class="title text-bold">Factor</td>
                            <td class="colon">:</td>
                            <td >{!! strtoupper($proposal->risk_factor) !!}</td>
                        </tr>
                        <tr>
                            <td class="number text-bold">ii.</td>
                            <td class="text-bold">Technical Risk</td>
                            <td >:</td>
                            <td >{!! strtoupper($proposal->risk_technical) !!}</td>
                        </tr>

                        <tr >
                            <td class="number text-bold">ii.</td>
                            <td class="text-bold">Timing Risk</td>
                            <td >:</td>
                            <td >{!! strtoupper($proposal->risk_timing) !!}</td>
                        </tr>
                        
                        <tr >
                            <td class="number text-bold">ii.</td>
                            <td class="title text-bold">Budget Risk</td>
                            <td class="colon">:</td>
                            <td >{!! strtoupper($proposal->risk_budget) !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="text-bold">
                <td class="number">5.</td>
                <td >Completion schedule</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2">
                    <table class="sub-table line-height-2">
                        <tr class="text-bold">
                            <td class="number">i.</td>
                            <td class="title">Starting Date</td>
                            <td class="colon">:</td>
                            <td >{{ optional($proposal->schedule_start_date)->format("Y-m") }}</td>
                        </tr>
                        <tr class="text-bold">
                            <td class="number">ii.</td>
                            <td class="title">Completion Date</td>
                            <td class="colon">:</td>
                            <td >
                                {{ 
                                    $proposal->schedule_start_date 
                                        ? optional($proposal->schedule_start_date)->addMonths($proposal->schedule_duration - 1)->format("Y-m") 
                                        : ' - '
                                }}</td>
                        </tr>
                        <tr class="text-bold">
                            <td class="number">iii.</td>
                            <td class="title">Duration</td>
                            <td class="colon">:</td>
                            <td >{{ $proposal->schedule_duration }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </section>
</div>