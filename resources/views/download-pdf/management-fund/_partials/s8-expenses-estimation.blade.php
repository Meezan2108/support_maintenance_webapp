

<style>
    
</style>

<div id="s8-expenses-estimation">
    <section  class="line-height-3">
        <div class="mb-1">
            <h2 class="title-1  text-bold">
                <span class="number">H.</span>
                Direct Expenses Estimation 
            </h2>
        </div>
    </section>

    <section class="line-height-2">
        <table class="table-border " width="100%">
            <tr>
                <th class="align-left">Expense Categories and Items</th>
                @foreach($arrYear as $year)
                <th>{{ $year }}</th>
                @endforeach
                <th>Total</th>
            </tr>

            @php
                $totalCost = 0;
                $arrTotal = collect($arrYear)->map(function() {
                    return 0;
                })->toArray();
            @endphp

            @foreach($costSeries as $series)
                <tr>
                    <th class="align-left" colspan="{{ count($arrYear) + 2 }}">
                        {{ $series->description }} ( {{ $series->vseries_code }} )
                    </th>
                <tr>
                
                @if(count($expensesEstimation[$series->vseries_code]) == 0)
                    <td colspan="{{ count($arrYear) + 2 }}">&nbsp;</td>
                @endif
                @foreach($expensesEstimation[$series->vseries_code] as $item)

                @php $totalCost += $item["cost"] @endphp

                <tr>
                    <td>{{ $item["description"] }}
                    @foreach($item["years"] as $key => $costYear)
                        @if(isset($arrTotal[$key])) 
                            @php $arrTotal[$key] += $costYear @endphp
                            <td class="align-right">{{ number_format($costYear) }}</td>
                        @endif
                    @endforeach
                    <td class="align-right">{{ number_format($item["cost"]) }}</td>
                </tr>
                @endforeach
            
            @endforeach
            <tr>
                <th class="align-left">Total direct expenses</th>
                @foreach($arrTotal as $total)
                <th class="align-right">{{ number_format($total) }}</th>
                @endforeach
                <th class="align-right">{{ number_format($totalCost) }}</th>
            </tr>
        </table>
    </section>
</div>