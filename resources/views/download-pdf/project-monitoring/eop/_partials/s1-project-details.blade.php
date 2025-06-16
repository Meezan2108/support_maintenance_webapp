<style>
    
</style>

<div id="s1-project-identification">
    <section>
        <div class="mb-4">
            <center>
                <h2 class="title-1">
                    {{ $eop->proposal->proposal_type == 1 ? 'TRF' : 'EXTERNAL FUND' }}
                    END OF PROJECT
                </h2>
            </center>
        </div>

    </section>

    <section>
        <div class="border p-1">
            <div class="mb-1">
                <h3 class="title-1"><span class="number">A.</span> Description of the Project</h3>
            </div>
            <table class="table-noborder">
                <tr>
                    <td class="number">1.</td>
                    <td class="title">Project Number</td>
                    <td class="colon">:</td>
                    <td>{{ $report['project_details']['proposal']['project_number'] }}</td>
                </tr>
                <tr>
                    <td class="number">2.</td>
                    <td>PTJ Code</td>
                    <td>:</td>
                    <td>{{ implode(", ",$report['project_details']['proposal']['ptj_code'] ?? []) }}</td>
                </tr>
                <tr>
                    <td class="number">3.</td>
                    <td>Project Title</td>
                    <td>:</td>
                    <td>{{ $report['project_details']['proposal']['project_title'] }}</td>
                </tr>
                <tr>
                    <td class="number">4.</td>
                    <td>Project Leader</td>
                    <td>:</td>
                    <td>{{ $report['project_details']['proposal']['researcher']['name'] }}</td>
                </tr>
                <tr>
                    <td >5.</td>
                    <td >Project Team</td>
                    <td >:</td>
                    <td></td>
                </tr>
            </table>
            <div class="margin-tab1 mt-1 mb-1">
                <table class="table-border " style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['project_details']['proposal']['teams'] as $k => $v)
                            <tr>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $v['name'] }}</td>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $mapTeamType[$v['type']] ?? "Staff" }}</td>
                                <td style="font-weight: lighter;border: 1px solid #444;">{{ $v['organization'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <table class="table-noborder">
                <tr>
                    <td class="number">6.</td>
                    <td class="title">Project Duration</td>
                    <td class="colon">:</td>
                    <td>{{ $report['project_details']['proposal']['schedule_duration'] }} Month</td>
                </tr>
                <tr>
                    <td class="number">7.</td>
                    <td>Budget Approved</td>
                    <td>:</td>
                    <td>RM {{ number_format($report['project_details']['proposal']['approved_cost']) }}</td>
                </tr>
            </table>
        </div>

        <div class="border p-1">
            <div class="mb-1">
                <h3 class="title-1" style="margin-top: 0px; margin-bottom:0px;">
                    <span class="number">B.</span> Objectives of the project
                </h3>
            </div>
            
            <table class="table-noborder">
                <tr>
                    <td class="number"> 1. </td>
                    <td>
                        <div class="text-bold">
                            Socio-economic Objectives (SEO)
                        </div>
                        <span>
                            <p style="font-style: italic;">
                                Which socio-economic objectives are addressed by the project? (Please
                                identify the Research Priority Area , SEO Category and SEO Group under
                                which the project falls. Refer to the Malaysian Research & Development
                                Classification System (MRDCS) – 6th edition)
                            </p>
                        </span>
                    </td>
                </tr>
            </table>

            <table class="table-noborder margin-tab1">
                <tr>
                    <td class="title">Research Priority Area</td>
                    <td class="colon">:</td>
                    <td >{{ optional($eop->proposal->seoArea)->description }}</td>
                </tr>
                <tr>
                    <td>SEO Category</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->seoCategory)->description }}</td>
                </tr>
                <tr>
                    <td>SEO Group</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->seoGroup)->description }}</td>
                </tr>
            </table>
                    
            
            <table class="table-noborder mt-1">
                <tr>
                    <td class="number">2.</td>
                    <td colspan="2"> 
                        <div class="text-bold">
                            Fields of Research (FOR)
                        </div>
                        <span>
                            <p style="font-style: italic;">
                                Which are the two main FOR Categories, FOR Groups, and FOR Areas of your
                                project? (Please refer to the Malaysian Research & Development
                                Classification System (MRDCS) – 6th edition)
                            </p>
                        </span>
                    </td>
                </tr>
            </table>
            <table class="table-noborder margin-tab1">
                <tr>
                    <td class="number">a.</td>
                    <td >Primary field of research</td>
                    <td ></td>
                </tr>
            </table>
            <table class="table-noborder margin-tab2">
                <tr>
                    <td class="title">FOR Category</td>
                    <td class="colon">:</td>
                    <td>{{ optional($eop->proposal->primaryResearchField)->category->description ?? "" }}</td>
                </tr>
                <tr>
                    <td>FOR Group</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->primaryResearchField)->group->description ?? "" }}</td>
                </tr>
                <tr>
                    <td>FOR Area</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->primaryResearchField)->area->description ?? "" }}</td>
                </tr>
            </table>

            <table class="table-noborder margin-tab1">
                <tr>
                    <td class="number">b.</td>
                    <td >Secondary Field of research</td>
                    <td ></td>
                </tr>
            </table>
            <table class="table-noborder margin-tab2">
                <tr>
                    <td class="title">FOR Category</td>
                    <td class="colon">:</td>
                    <td>{{ optional($eop->proposal->secondaryResearchField)->category->description ?? "" }}</td>
                </tr>
                <tr>
                    <td>FOR Group</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->secondaryResearchField)->group->description ?? "" }}</td>
                </tr>
                <tr>
                    <td>FOR Area</td>
                    <td>:</td>
                    <td>{{ optional($eop->proposal->secondaryResearchField)->area->description ?? "" }}</td>
                </tr>
            </table>
        </div>
        

        <div class="border p-1">
            <div class="mb-1">
                <h3 class="title-1"><span class="number">C.</span> Objectives achievement</h3>
            </div>
            
            <div class="margin-tab1 mb-2">
            
                <div class="text-bold">
                    Original project objectives
                </div>
                <span style="font-style: italic;" class="mb-1">
                    (Please state the specific project objectives as described in Section II of the Application Form)
                </span>
            
                @foreach($eop->proposal->objectives as $objective)
                    <div class="text-justified mt-0">
                    {!! $objective->description !!}
                    </div>
                @endforeach

            </div>

            <div class="margin-tab1 mb-2">
                <div class="text-bold">
                    Objectives Achieved
                </div>
                <span style="font-style: italic;">
                    (Please state the extent to which the project objectives were achieved)
                </span>
                @foreach($eop->objective->where("status", \App\Models\Objectiveable::STATUS_ACHIEVED) as $objective)
                    <div class="text-justified">
                    {!! $objective->description !!}
                    </div>
                @endforeach
            </div>
                </li>
            </ul>
            
            <div class="margin-tab1 mb-2">

                <div class="text-bold">
                    Objectives not achieved
                </div>
                <span style="font-style: italic;">
                    (Please identify the objectives that were not achieved and give reasons)
                </span>

                @foreach($eop->objective->where("status", \App\Models\Objectiveable::STATUS_NOT_ACHIEVED) as $objective)
                    <div class="text-justified">
                    {!! $objective->description !!}
                    </div>
                @endforeach
            </div>

        </div>
        
        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1">
                    <span class="number">D.</span> Technology Transfer/Commercialisation Approach, if any.
                </h3>
                <span>
                    <p style="font-style:italic;">(Please describe the approach
                        planned to transfer/commercialise the results of the project)
                    </p>
                </span>
            </div>
            <div class="text-justified">
                {!! $report['technology']['tech_approach'] !!}
            </div>
        </div>


        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1"><span class="number">E.</span>Assessment of Research Approach</h3>
                <span>
                    <p style="font-style:italic;">
                        (Please highlight the main steps actually performed and indicate any major departure from the planned approach or any major difficulty encountered)
                    </p>
                </span>
            </div>
            <div class="text-justified">
                {!! $report['assessment']['asses_research'] !!}
            </div>
        </div>

        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1">
                    <span class="number">F.</span> Assessment of the Project Schedule</h3>
                <span>
                    <p style="font-style:italic;">
                        (Please make any relevant comment regarding the actual duration of the project and highlight any significant variation from plan)
                    </p>
                </span>
            </div>
            <div class="text-justified">
                {!! $report['assessment']['asses_schedule'] !!}
            </div>
        </div>

        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1"><span class="number">G.</span>Assessment of Project Costs</h3>
                <span>
                    <p style="font-style: italic;">
                        (Please comment on the appropriateness of the original budget and highlight any major departure from the planned budget)
                    </p>
                </span>
            </div>
            <div class="text-justified">
                {!! $report['assessment']['asses_cost'] !!}
            </div>
        </div>

        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1">
                    <span class="number">H.</span> Additional Project Funding Obtained</h3>
                <p style="font-style:italic;">(In case of involvement of other funding sources, please indicate the source and total funding provided)</p>
            </div>
            <div class="text-justified">
                {!! $report['additional_funding']['additional_fund'] !!}
            </div>
        </div>


        <div class="border p-1">
            <div >
                <h3 class="title-1 mb-1">
                    <span class="number">I.</span> Benefits of the Project</h3>
                <span>
                    <p style="font-style: italic">
                        (Please identify the actual benefits arising from the project as defined
                        in Section III of the Application Form. For examples of outputs,
                        organisational outcomes and sectoral/national impacts, please refer to
                        Section III of the Guidelines for the Application of R&D Funding under
                        ScienceFund)
                    </p>
                </span>
            </div>

            <div class="text-justified ">
                @foreach($questionsBenefit as $group)
                @include('download-pdf.project-monitoring.eop._partials.wrapper-benefits-group', [
                    'questionGroup' => $group,
                    'answers' => $report['benefits']['answers']
                ])
                @endforeach
            </div>
        </div>

        <table class="table-border" width="100%">
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
    </section>
</div>