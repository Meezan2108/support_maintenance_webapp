<style>
    
</style>

<div >
    <section>
        <div class="mb-4">
            <h2 class="title-1">
                {{ $mar->proposal->proposal_type == 1 ? 'TRF' : 'EXTERNAL FUND' }}
                MILESTONE ACHIEVEMENT REPORT</h2>
        </div>

    </section>

    <section>
        <div class="border p-1">
            <div class="mb-1">
                <h3 class="title-1"><span class="number">A.</span></h3>
            </div>
            <div>
                <table class="table-noborder">
                    <tr>
                        <td style="width: 25%; padding-left:20px;" colspan="2">Project Number</td>
                        <td style="width: 75%">: {{ $mar->proposal->project_number }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; padding-left:20px;" colspan="2">PTJ Code</td>
                        <td style="width: 75%">: {{ implode(", ",$mar->proposal->ptj_code ?? []) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; padding-left:20px;" colspan="2">Project Title</td>
                        <td style="width: 75%">: {{ $mar->proposal->project_title }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; padding-left:20px;" colspan="2">Project Leader</td>
                        <td style="width: 75%">: {{ $mar->proposal->researcher->name }}</td>
                    </tr>
                    <tr>
                    <tr>
                        <td style="width:30%; padding-left:20px;">Tel : {{ $mar->proposal->researcher->tel_no }} </td>
                        <td style="width:30%; padding-left:40px;">Fax : {{ $mar->proposal->researcher->fax_no }} </td>
                        <td style="width:30%; padding-left:50px;">Email : {{ $mar->proposal->researcher->email }} </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="border p-1">      
            <div class="mb-1">
                <h3 class="title-1"><span class="number">B.</span> Milestone Achievement</h3>
            </div>
            <div>
                <table class="table-border line-height-1" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th><center>No.</center></th>
                            <th><center>Planned Milestone</center></th>
                            <th><center>Planned Milestone Date</center></th>
                            <th><center>Achieved *(Yes/No)</center></th>
                            <th><center>Actual Completion Date (month/year)</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($milestones as $k => $v)
                            <tr>
                                <td style="font-weight: lighter;"><center>M{{ ($k) + 1 }}</center></td>
                                <td style="font-weight: lighter;">{{ $v->activities }}</td>
                                <td style="font-weight: lighter;">{{ $v->from->format('Y-m-d') }}</td>
                                <td style="font-weight: lighter;">@if($v->is_achieved == "1") Yes @else No @endif</td>
                                <td style="font-weight: lighter;">{{ $v->completion_date->format('M/Y') }}</td>
                            </tr>
                        @endforeach
                        @if($milestones->count() == 0)
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border p-1">
            <h3 class="title-1 line-height-1"><span class="number">C.</span> Planned milestone date</h3>

            <h4 class="title-1  mb-1  margin-tab1">Reasons for non-achievement :</h4>
            <div class="margin-tab1 text-justified">
                {!! $report['milestone_achievement']['reason_not_achieved'] !!}
            </div>

            <h4 class="title-1  mb-1 margin-tab1">Proposed adjustments/corrective actions :</h4>
            <div class="margin-tab1 text-justified">
                {!! $report['milestone_achievement']['corrective_action'] !!}
            </div>
            
            <h4 class="title-1 mb-1 margin-tab1">
                Revised milestone completion date :
                <span class="text-normal">{{ $report['milestone_achievement']['revised_completion_date'] }}</span>
            </h4>
        </div>

        <div class="border p-1">
            <h3 class="title-1 line-height-1"><span class="number">D.</span> Impact on project schedule</h3>

            <h4 class="title-1  margin-tab1">
                Request for extension :
                <span class="text-normal"> {{ $report['milestone_achievement']['request_extension'] }} (Months)</span>
            </h4>

            <h4 class="title-1  margin-tab1">
                New date of project completion :
                <span class="text-normal"> {{ $report['milestone_achievement']['new_completion_date'] }}</span>
            </h4>
            
            
            <h4 class="title-1  margin-tab1">Reasons for extension :</h4>
            <div class="margin-tab1 text-justified">
                {!! $report['milestone_achievement']['reason_for_extension'] !!}
            </div>
        </div>

        <div class="border p-1">
            <h3 class="title-1 line-height-1"><span class="number">E.</span> Project Achivement</h3>
                
            <h4 class="title-1  margin-tab1"><span class="number">i.</span> Intellectual Property Rights</h4>
            <span>
                <p style="font-style: italic;">
                    (Patent, Industrial Design, Trademark, Copyrights etc)
                </p>
            </span>
            <div class=" margin-tab2">
                <table class="table-noborder" width="100%">
                    @foreach($report['project_achievement']['ipr'] as $k => $v)
                        <tr>
                            <td class="number">{{ ($k) + 1 }}</td>
                            <td >{{ $v['output'] }}</td>
                            <td >{{ $v['date']->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="border p-1">
            
            <h4 class="title-1 margin-tab1"><span class="number">ii.</span> Publications and papers</h4>
            <span>
                <p style="font-style: italic;">
                    (International, national, books, citation etc)
                </p>
            </span>
            <div class="margin-tab2">
                <table class="table-noborder" width="100%">
                    @foreach($report['project_achievement']['publications'] as $k => $v)
                        <tr>
                            <td class="number">{{ ($k) + 1 }}</td>
                            <td >{{ $v['title'] }}</td>
                            
                            <td >({{ $v['publisher'] }})</td>
                            <td >{{ $v['date']->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        
        <div class="border p-1">
            
            <h4 class="title-1 margin-tab1"><span class="number">iii.</span> Expertise Developemnt</h4>
            <span>
                <p style="font-style: italic;">
                    (PhD, Masters, Research Staff with new speciality)
                </p>
            </span>
            <div class="margin-tab2">
                <table class="table-noborder" width="100%">
                    @foreach($report['project_achievement']['expertise_development'] as $k => $v)
                        <tr>
                            <td class="number">{{ ($k) + 1 }}</td>
                            <td >{{ $v['output'] }}</td>
                            <td >{{ $v['date']->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="border p-1">
            <h4 class="title-1 margin-tab1"><span class="number">iv.</span> Prototype</h4>
            <span>
                <p style="font-style: italic;">
                    (Prototype name, type eg. Lab Scale, engineering scale, commercial scale etc)
                </p>
            </span>
            <div class="margin-tab2">
                <table class="table-noborder" width="100%">
                    @foreach($report['project_achievement']['prototype'] as $k => $v)
                        <tr>
                            <td class="number">{{ ($k) + 1 }}</td>
                            <td >{{ $v['output'] }}</td>
                            <td >{{ $v['date']->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="border p-1">
            
            <h4 class="title-1 margin-tab1"><span class="number">v.</span> Commercialisation</h4>
            <span>
                <p style="font-style: italic;">
                    (Lincensing, royalty, spin-off, direct sale etc)
                </p>
            </span>
            <div class="margin-tab2">
                <table class="table-noborder" width="100%">
                    @foreach($report['project_achievement']['commercialization'] as $k => $v)
                        <tr>
                            <td class="number">{{ ($k) + 1 }}</td>
                            <td >{{ $v['name'] }}</td>
                            <td >{{ $v['taker'] }}</td>
                            <td >{{ $v['category_description'] }}</td>
                            <td >{{ $v['date']->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="border p-1">
            <div class="line-height-1">
                <h3 class="title-1"><span class="number">F.</span> General Comment</h3>
            </div>
            <div class="line-height-1" style="font-size:14px;">
                {!! $report['commentary']['comments'] !!}
            </div>
        </div>

        <div class="border p-1">
            <div style="height: 100px;">
                <table class="sub-table-no-border">
                    <tr>
                        <td style="width: 400px;">Date:</td>
                        <td>Signature:</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</div>