<style>
    
</style>

<div id="s2-progress" class="">
    <section>
        <div class="border p-1">
            <table class="table-noborder" width="100%">
                <tr>
                    <td colspan="4"><h4>Progress</h4></td>
                </tr>
                <tr>
                    <td  colspan="4">
                        <div class="mb-1">Date</div>
                        <div style="font-weight: lighter;" class=" mb-1">
                        {{ $report->date->format("Y-m-d") }}
                        </div>
                    </td>
                </tr>
            </table>
                
            <div>Summary</div>
            <div class="text-justified">
                {!! $report->summary !!}
            </div>
        </div>
    </section>
</div>