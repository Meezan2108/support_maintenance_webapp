<div id="publications-achhievement" class="line-height-1">

    <section>
        <div class="mb-1">
            <h2 class="title-1">KPI Achievement for R&D Publications in {{ $year }}.</h2>
        </div>
    </section>
    <section class="mb-2">
        <table class="table-border">
            <tr>
                <th>Type of Publications</th>
                <th width="10%">KPI {{ $year }}</th>
                <th width="10%">Target 1st Half {{ $year }}</th>
                <th width="10%">Achieved 1st Half {{ $year }}</th>
                <th width="10%">Target 2nd Half {{ $year }}</th>
                <th width="10%">Achieved 2nd Half {{ $year }}</th>
                <th width="10%" rowspan="{{ $pubTypes->count() + 1 }}">Total</th>
            </tr>

            @php
            $totalAchieved = 0;
            @endphp

            @foreach($pubTypes as $index => $type)
            <tr>
                <td>{{ $type->description }}</td>

                @if($index == 0)
                <td class="align-center" rowspan="{{ $pubTypes->count() }}">
                    {{ $targetPeriod->sum("target") }}
                </td>
                @endif
                
                @foreach($targetPeriod as $period)

                @php
                    $pubTypePeriod = $period->pubTypes->firstWhere("id", $type->id);
                    $achievement = optional($pubTypePeriod)->publications_count ?? 0;
                    $totalAchieved += $achievement;
                @endphp

                @if($index == 0)
                <td class="align-center" rowspan="{{ $pubTypes->count() }}">{{ $period->target }}</td>
                @endif
                <td class="align-center">{{ $achievement }}</td>
                @endforeach

            </tr>
            @endforeach

            <tr>
                <th>Total</th>
                <th> {{ $targetPeriod->sum("target") }} </th>
                
                @php
                    $total = 0;
                @endphp

                @foreach($targetPeriod as $period)
                @php
                $subTotal = $period->pubTypes->sum("publications_count");
                $total += $subTotal;
                @endphp

                <th>{{ $period->target }}</th>
                <th>{{ $subTotal }}</th>
                @endforeach

                <th>{{ $total }}</th>
            </tr>
        </table>
    </section>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <th width="10pt">No</th>
                <th>Title</th>
                <th width="30pt">Type Of Publication</th>
            </tr>
            @foreach($publications as $index => $publication)
            <tr>
                <td>{{ ($index + 1) }}</td>
                <td>{{ $publication->title }}</td>
                <td>{{ $publication->type->description }}</td>
            </tr>
            @endforeach
        </table>
    </section>
</div>