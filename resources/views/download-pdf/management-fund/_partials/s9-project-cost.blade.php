

<style>
    
</style>

<div id="s8-project-cost">
    <section  class="line-height-3">
        <div class="mb-1">
            <h2 class="title-1  text-bold">
                <span class="number">I.</span>
                Project Cost 
            </h2>
        </div>
    </section>

    <section class="line-height-2">
        <table class="table-noborder" width="100%">

            <tr class="text-bold">
                <td class="number">1.</td>
                <td class="pb-2">Salaried Personal Cost</td>
            </tr>


            @php
                $totalCost = 0;
                $arrTotal = collect($arrYear)->map(function() {
                    return 0;
                })->toArray();
            @endphp

            <tr>
                <td colspan="2">
                    <table class="table-border mb-2" width="100%">
                        <tr class="align-center">
                            <th rowspan="2">Staff Category</th>
                            @foreach($arrYear as $key => $year)
                            <th width="40pt" class="align-center">Year {{ $key + 1 }} (RM)</th>
                            @endforeach
                            <th width="40pt" rowspan="2" class="align-center">Total <br /> (RM)</th>
                        </tr>
                        <tr  >
                            @foreach($arrYear as $key => $year)
                            <th class="align-center">{{ $year }}</th>
                            @endforeach
                        </tr>

                        @foreach($expensesEstimation["V11000"] as $item)

                        @php $totalCost += $item["cost"] @endphp
        
                        <tr>
                            <td>{{ $item["description"] }} (V11000)</td>
                            @foreach($item["years"] as $key => $costYear)
                                @if(isset($arrTotal[$key]))
                                    @php $arrTotal[$key] += $costYear @endphp
                                    <td class="align-right">{{ number_format($costYear) }}</td>
                                @endif
                            @endforeach
                            <td class="align-right">{{ number_format($item["cost"]) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>


            <tr class="text-bold">
                <td class="number">2.</td>
                <td class="pb-2">Direct Project Expenses</td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="table-border mb-2" width="100%">
                        <tr>
                            <th rowspan="2">Expense Category</th>
                            @foreach($arrYear as $key => $year)
                            <th width="40pt" class="align-center">Year {{ $key + 1 }} (RM)</th>
                            @endforeach
                            <th width="40pt" rowspan="2" class="align-center">Total <br /> (RM)</th>
                        </tr>
                        <tr  >
                            @foreach($arrYear as $key => $year)
                            <th class="align-center">{{ $year }}</th>
                            @endforeach
                        </tr>

                        @php
                        
                        $expensesEstimation = collect($expensesEstimation)->map(function($items) use ($arrYear) {
                            $arrTotalYear = collect($arrYear)->map(function() {
                                return 0;
                            })->toArray();

                            foreach($items as $item) {
                                foreach($item["years"] as $key => $yearCost) {
                                    if(!isset($arrTotalYear[$key])) continue;
                                    $arrTotalYear[$key] += $yearCost;
                                }
                            }

                            return $arrTotalYear;
                        })->toArray();

                        $arrSubTotal = collect($arrYear)->map(function() {
                            return 0;
                        })->toArray();

                        @endphp

                        @foreach($costSeries as $item)

                        <tr>
                            <td>{{ $item["description"] }} ({{ $item["vseries_code"] }})</td>
                            @foreach($expensesEstimation[$item["vseries_code"]] as $key => $costYear)
                                @if(isset($arrTotal[$key]))
                                    @php $arrSubTotal[$key] += $costYear @endphp
                                    @php $arrTotal[$key] += $costYear @endphp
                                    <td class="align-right">{{ number_format($costYear) }}</td>
                                @endif
                            @endforeach
                            <td class="align-right">{{ number_format(collect($expensesEstimation[$item["vseries_code"]])->sum()) }}</td>
                        </tr>

                        @endforeach

                        <tr class="align-center">
                            <th >Total Direct</th>
                            @foreach($arrSubTotal as $key => $total)
                            <th width="40pt" class="align-right">{{ number_format($total) }}</th>
                            @endforeach
                            <th width="40pt" class="align-right">
                                {{ number_format(collect($arrSubTotal)->sum()) }}
                            </th>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr class="text-bold">
                <td class="number">3.</td>
                <td class="pb-2">Total Project Cost</td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="table-border mb-2" width="100%">
                        <tr class="align-center">
                            @foreach($arrYear as $key => $year)
                            <th width="30%"  class="align-center">Year {{ $key + 1 }} (RM)</th>
                            @endforeach
                            <th width="30%" rowspan="2" class="align-center">Total <br /> (RM)</th>
                        </tr>
                        <tr  >
                            @foreach($arrYear as $key => $year)
                            <th class="align-center">{{ $year }}</th>
                            @endforeach
                        </tr>

                        <tr  >
                            @foreach($arrTotal as $key => $cost)
                            <td class="align-right">{{ number_format($cost) }}</td>
                            @endforeach
                            <td class="align-right">{{ number_format(collect($arrTotal)->sum()) }}</td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </section>
</div>