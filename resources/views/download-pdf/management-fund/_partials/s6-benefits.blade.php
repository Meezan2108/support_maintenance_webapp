
<style>
    
</style>

<div id="s1-project-identification">
    <section>
        <div class="mb-1">
            <h2 class="title-1  text-bold"><span class="number">F.</span> Benefits of the project</h2>
        </div>

    </section>
    <section>
        <table class="table-noborder line-height-1">
            
            <tr class="text-bold">
                <td class="number">1.</td>
                <td >Output expected from the project</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2 line-height-2">
                    <table class="table-border mt-1" width="100%">
                        <tr>
                            <th>Reseaerch</th>
                            <th width="90pt">Quantity</th>
                            <th width="180pt">Details/Remark</th>
                        </tr>
                        @foreach($benefitsOutput as $benefit)
                            @php
                                $output = $proposal->benefits
                                    ->where("ref_proposal_benefits_category_id", $benefit->id)
                                    ->first();
                            @endphp
                            <tr>
                                <td width="">{{ $benefit->description }}</td>
                                <td>{{ optional($output)->quantity }}</td>
                                <td>{{ optional($output)->detail }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

            <tr class="text-bold">
                <td class="number">2.</td>
                <td >Human capital and expert development</td>
            </tr>
            <tr >
                <td></td>
                <td class="pb-2 line-height-2">
                    <table class="table-border mt-1" width="100%">
                        <tr>
                            <th></th>
                            <th width="90pt">Quantity</th>
                            <th width="180pt">Specialisation Area (specific area)</th>
                        </tr>
                        @foreach($benefitsHuman as $benefit)
                            @php
                                $output = $proposal->benefits
                                    ->where("ref_proposal_benefits_category_id", $benefit->id)
                                    ->first();
                            @endphp
                            <tr>
                                <td width="">{{ $benefit->description }}</td>
                                <td>{{ optional($output)->quantity }}</td>
                                <td>{{ optional($output)->detail }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
        <table class="table-noborder line-height-1">
            <tr class="text-bold line-height-2">
                <td class="number">3.</td>
                <td >Economic contributions of the project</td>
            </tr>
        </table>
            
        @foreach($proposal->economicContribution as $contribution)
            <div class="text-justified margin-tab1">
            {!! $contribution->description !!}
            </div>
        @endforeach

    </section>
</div>