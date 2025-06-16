<div id="publications-achhievement" class="line-height-1">

    <section>
        <div class="mb-1">
            <h2 class="title-1">KPI Achievement for R&D Output in {{ $year }}.</h2>
        </div>
    </section>
    <section class="mb-2">
        <table class="table-border">
            <tr>
                <th>Type of Outputs</th>
                <th width="10%">KPI {{ $year }}</th>
                <th width="10%">Target 1st Half {{ $year }}</th>
                <th width="10%">Achieved 1st Half {{ $year }}</th>
                <th width="10%">Target 2nd Half {{ $year }}</th>
                <th width="10%">Achieved 2nd Half {{ $year }}</th>
                <th width="10%">Total  {{ $year }}</th>
            </tr>

            @php
            $totalAchieved = 0;
            @endphp

            @foreach($outputTypes as $index => $type)
            <tr>
                
                @php
                    $subTotalAchieved = 0;
                    $totalTarget = $targetPeriod->sum(function ($item) use ($type) {
                        $targetTypePeriod = $item->targets
                            ->firstWhere('sub_category_id', $type->targetKpiCategory->id);
                        return optional($targetTypePeriod)->target ?? 0;
                    });
                @endphp

                <td>{{ $type->description }}</td>

                <td class="align-center">{{ $totalTarget }}</td>

                @foreach($targetPeriod as $period)

                @php
                    $outputTypePeriod = $period->outputTypes->firstWhere("id", $type->id);
                    $targetTypePeriod = $period->targets->firstWhere('sub_category_id', $type->targetKpiCategory->id);

                    $achievement = optional($outputTypePeriod)->output_count ?? 0;
                    $totalAchieved += $achievement;
                    $subTotalAchieved += $achievement;
                    $target = optional($targetTypePeriod)->target ?? 0;
                @endphp
                
                <td class="align-center">{{ $target }}</td>
                <td class="align-center">{{ $achievement }}</td>
                @endforeach

                <td class="align-center">{{ $subTotalAchieved }}</td>
            </tr>
            @endforeach
{{-- 
            <tr>
                <th>Total</th>
                <td> {{ $targetPeriod->sum("target") }} </td>
                
                @foreach($targetPeriod as $period)

                <td class="align-center">{{ $period->targets->sum("target") }}</td>
                <td class="align-center">{{ $period->pubTypes->sum("output_count") }}</td>

                @endforeach

                <td class="align-center">{{ $totalAchieved }}</td>
            </tr> --}}
        </table>
    </section>

    <section>
        <table class="table-border" width="100%" style="font-size:10pt">
            <tr>
                <th width="5pt">No</th>
                <th width="20%">Output</th>
                <th width="10%">Type Of Output</th>
                <th width="10%">Project Leader</th>
                <th width="20pt" class="break-word">Status</th>
                <th width="10%">Date Output Developed</th>
                {{-- <th width="10%">Name of Project Involved</th> --}}
                <th width="10%">Source Of Projet</th>
            </tr>
            @foreach($outputs as $index => $output)
            <tr>
                <td>{{ ($index + 1) }}</td>
                <td>{{ $output->output }}</td>
                <td>{{ $output->output_type->description }}</td>
                <td>{{ $output->projectLeader->name }}</td>
                <td  class="break-word">{{ $output->output_status->description }}</td>
                <td>{{ $output->date_output->format("M-y") }}</td>
                <td>{{ $output->source_project }}</td>
            </tr>
            @endforeach
        </table>
    </section>
</div>