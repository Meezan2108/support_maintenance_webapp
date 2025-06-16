<div id="publications-achhievement" class="line-height-1">

    <section>
        <div class="mb-1">
            <h2 class="title-1">KPI Achievement for R&D IPR in {{ $year }}.</h2>
        </div>
    </section>
    <section class="mb-2">
        <table class="table-border">
            <tr>
                <th>Parameter</th>
                <th width="10%">KPI {{ $year }}</th>
                <th width="10%">Target 1st Half {{ $year }}</th>
                <th width="10%">Achieved 1st Half {{ $year }}</th>
                <th width="10%">Target 2nd Half {{ $year }}</th>
                <th width="10%">Achieved 2nd Half {{ $year }}</th>
                <th width="10%">Total</th>
            </tr>

            @php
            $totalAchieved = 0;
            @endphp

            <tr>
                <td>Intellectual Property Right (IPR)</td>
                <td class="align-center">
                    {{ $targetPeriod->sum("target") }}
                </td>
                
                @foreach($targetPeriod as $period)
                <td class="align-center">{{ $period->target }}</td>
                <td class="align-center">{{ $period->achievement }}</td>
                @endforeach

                <td class="align-center">
                    {{ $targetPeriod->sum("achievement") }}
                </td>
            </tr>

        </table>
    </section>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <th width="10pt">No</th>
                <th  width="200pt">Output</th>
                <th>Type of IPR</th>
                <th>Project Leader</th>
            </tr>
            @foreach($ipr as $index => $item)
            <tr>
                <td>{{ ($index + 1) }}</td>
                <td>{{ $item->output }}</td>
                <td>{{ $item->patent->description }}</td>
                <td>{{ $item->projectLeader->name }}</td>
            </tr>
            @endforeach
        </table>
    </section>
</div>