<style>
#cover-wrapper {
    padding-top: 50pt;
}


</style>
<div id="cover-wrapper">
    <div class="align-center mb-3">
        <h3>
        {{ $proposal->proposal_type == 1 ? "TRF" : "External Fund" }} Application
        </h3>
    </div>

    <div class="align-center mb-3">
        <img src="{{ public_path('/assets/images/st-adv-logo.png') }}" width="150px"/>
    </div>

    <div class="text-wrapper" style="height: 500pt; position: relative;">
        <div class="align-center m-3 border">
            <h2>{{ $proposal->project_title }}</h2>
        </div>

        <div class="m-3" style="position: absolute; bottom:0pt; left:0pt; right:0pt">
            <div class="mb-3">
                <strong>Prepared By:</strong>
            </div>
            <div class="mb-3">
                <table class="table-noborder">
                    <tr>
                        <th style="width:30%">Name</th>
                        <th>: {{ optional($proposal->researcher)->name }}</th>
                    </tr>
                    <tr>
                        <th>Division</th>
                        <th>: {{ optional(optional($proposal->researcher)->division)->description }}</th>
                    </tr>
                    <tr>
                        <th>Institution</th>
                        <th>: {{ $proposal->institution }}</th>
                    </tr>
                    <tr>
                        <th>Date Submitted</th>
                        <th>: {{ $proposal->created_at->format("Y-m-d") }}</th>
                    </tr>
                </table>
            </div>
        </div>

    </div>  

</div>