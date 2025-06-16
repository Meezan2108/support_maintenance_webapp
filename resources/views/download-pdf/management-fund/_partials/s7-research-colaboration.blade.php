

<style>
    
</style>

<div id="s1-project-identification" class="line-height-3">
    <section>
        <div class="mb-1">
            <h2 class="title-1  text-bold"><span class="number">G.</span> Research Collaboration</h2>
        </div>

    </section>
    <section class="line-height-1">
        <table class="table-noborder line-height-1">
            
            <tr class="text-bold">
                <td class="number">1.</td>
                <td >Institutions Involved in the Project</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2 line-height-2">
                    <table class="table-border mt-1" width="100%">
                        <tr>
                            <th>Organisations Involved</th>
                            <th width="90pt">Other</th>
                            <th width="180pt">Role</th>
                        </tr>
                        @foreach($organisations as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td >{{ $item->other }}</td>
                            <td >{{ $item->role }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

            <tr class="text-bold">
                <td class="number">2.</td>
                <td >Industries Involved in the Project</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2 line-height-2">
                    <table class="table-border mt-1" width="100%">
                        <tr>
                            <th>Industry</th>
                            <th width="180pt">Role</th>
                        </tr>
                        @foreach($industries as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td >{{ $item->role }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>


            <tr class="text-bold">
                <td class="number">3.</td>
                <td >Project Team</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2 line-height-2">
                    <table class="table-border mt-1" width="100%">
                        <tr>
                            <th>Project Leader</th>
                            <th width="180pt">Organisation</th>
                            <th width="70pt">Man-Month</th>
                        </tr>
                        @foreach($teamLeader as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td >{{ $item->organization }}</td>
                            <td >{{ $item->man_month }}</td>
                        </tr>
                        @endforeach
                    </table>

                    <table class="table-border mt-2" width="100%">
                        <tr>
                            <th>Researcher</th>
                            <th width="180pt">Organisation</th>
                            <th width="70pt">Man-Month</th>
                        </tr>
                        @foreach($teamResearcher as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td >{{ $item->organization }}</td>
                            <td >{{ $item->man_month }}</td>
                        </tr>
                        @endforeach
                    </table>

                    <table class="table-border mt-2" width="100%">
                        <tr>
                            <th>Support Staff</th>
                            <th width="180pt">Organisation</th>
                            <th width="70pt">Man-Month</th>
                        </tr>
                        @foreach($teamStaff as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td >{{ $item->organization }}</td>
                            <td >{{ $item->man_month }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

            
        </table>
    </section>
</div>