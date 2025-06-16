@php
$splitArrYear = array_chunk($arrYear, 3);

@endphp

@foreach($splitArrYear as $splitKey => $splitYear)

@if($splitKey > 0)

<div class='mb-3'></div>
@endif

<div>
    <table class="table-timeline" width="100%">
        <tr>
            <th class="align-left title" rowspan="2">{{ $title }}</th>
            @foreach($splitYear as $year)
            <th colspan="12">{{ $year }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($splitYear as $year)
                @for($month = 1; $month <= 12; $month++)
                    <th width="12pt">{{ $month }}</th>
                @endfor
            @endforeach
        </tr>

        @foreach($activities as $activity)
        <tr>
            <td class="title">{{ $activity->activities }}</td>
            @foreach($splitYear as $year)
                @for($month = 1; $month <= 12; $month++)
                    <td @if(App\Helpers\DateHelper::isInrange($activity->from, $activity->to, $year, $month)) class="bg-mustard" @endif></td>
                @endfor
            @endforeach
        </tr>

        @endforeach
    </table>
</div>
@endforeach

<style>
.table-timeline {
    border-collapse: collapse;
}

.table-timeline th {
    background: #eee;
}

.table-timeline td, .table-timeline th {
    border: 1px solid #444;
    margin: 0px;
}

.table-timeline .title {
    padding: 1pt 6pt;
}

.bg-mustard {
    background: #ffdb58;
}
</style>
