<div id="publications-achhievement" class="line-height-1">

    <section>
        <div class="mb-1">
            <h2 class="title-1">KPI Achievement for R&D Project in {{ $year }}.</h2>
        </div>
    </section>
   
    <section>
        <table class="table-border" width="100%">
            <tr>
                <th rowspan="2" width="10pt">No</th>
                <th rowspan="2">Type Of Fund</th>
                <th rowspan="2" width="30pt">KPI {{ $year }}</th>
                <th colspan="3">Achievement {{ $year }}</th>
            </tr>
            <tr>
                <th width="30pt">P</th>
                <th width="30pt">O</th>
                <th width="30pt">C</th>
            </tr>
            @foreach($typeOfFundings as $index => $type)
            <tr>
                <td  class="align-center">{{ ($index + 1) }}</td>
                <td>{{ $type->description }}</td>
                <td class="align-center">{{ $type->target }}</td>
                <td class="align-center">{{ $type->pending_count }}</td>
                <td class="align-center">{{ $type->ongoing_count }}</td>
                <td class="align-center">{{ $type->completed_count }}</td>
            </tr>
            @endforeach
            <tr>
                <th ></th>
                <th >Total</th>
                <th >{{ $typeOfFundings->sum("target") }}</th>
                <th >{{ $typeOfFundings->sum("pending_count") }}</th>
                <th >{{ $typeOfFundings->sum("ongoing_count") }}</th>
                <th >{{ $typeOfFundings->sum("completed_count") }}</th>
            </tr>
        </table>
        <div class="mt-2">Note: P = Pending, O = Ongoing and C = Completed.</div>
    </section>

    <div class="mb-2"></div>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <th width="10pt">No</th>
                <th width="100pt">Type of Fund</th>
                <th>Project Title</th>
                <th width="100pt">Project Leader</th>
                <th width="100pt">Division</th>
            </tr>
            @foreach($proposals as $index => $proposal)
            <tr>
                <td>{{ ($index + 1) }}</td>
                <td>{{ $proposal->typeOfFund->description }}</td>
                <td>
                    {{ $proposal->project_title }}
                    <strong>{{ $proposal->reportEndProject->count() > 0 ? '(Project Complete)' : ''}}</strong>
                </td>
                <td>{{ $proposal->researcher->name }}</td>
                <td>{{ optional($proposal->researcher->division)->description }}</td>
            </tr>
            @endforeach
        </table>
    </section>
</div>