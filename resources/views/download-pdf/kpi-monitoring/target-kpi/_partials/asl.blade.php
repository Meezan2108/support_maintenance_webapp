<div id="publications-achhievement" class="line-height-1">

    <section>
        <div class="mb-1">
            <h2 class="title-1">KPI Achievement for R&D Analytical Service Lab in {{ $year }}.</h2>
        </div>
    </section>
    <section class="mb-2">
        <table class="table-border" style="width:100%">
            <tr>
                <th rowspan="2">Parameter</th>
                <th rowspan="2" width="10%">KPI {{ $year }}</th>
                <th width="10%" colspan="4">Achievement {{ $year }}</th>
                <th rowspan="2" width="10%">Total</th>
            </tr>
            <tr>
                <th width="10%">Q1</th>
                <th width="10%">Q2</th>
                <th width="10%">Q3</th>
                <th width="10%">Q4</th>
            </tr>

            @foreach($targetSubCategories as $subCategory)
            <tr>
                <td>{{ $subCategory->description }}</td>
                <td class="align-center">
                    {{ $subCategory->total_target }}
                </td>
                
                @foreach($subCategory->achievements as $achievement)
                <td class="align-center">{{ $achievement }}</td>
                @endforeach

                <td class="align-center">
                    {{ $subCategory->total_achievement }}
                </td>
            </tr>
            @endforeach
        </table>
    </section>

</div>