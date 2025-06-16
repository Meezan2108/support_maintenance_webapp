<style>
    
</style>

<div id="s1-project-identification" class="line-height-3 text-bold">
    <section>
        <div class="mb-1">
            <h2 class="title-1"><span class="number">A.</span> Project Identification</h2>
        </div>

    </section>
    <section>
        <table class="table-noborder">
            <tr>
                <td class="number">1.</td>
                <td class="title">Project Number</td>
                <td class="colon">:</td>
                <td>{{ $proposal->project_number }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Project Title</td>
                <td>:</td>
                <td>{{ $proposal->project_title }}</td>
            </tr>

            <tr>
                <td class="space">&nbsp;</td>
            </tr>

            <!-- item 2 project leader -->

            <tr>
                <td width="30pt">2.</td>
                <td width="125pt">Project Leader</td>
                <td width="5pt"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" >
                    <table class="sub-table">
                        <tr>
                            <td class="number">a.</td>
                            <td class="title">Name</td>
                            <td class="colon">:</td>
                            <td>{{ optional($proposal->researcher)->name }}</td>
                        </tr>
                        <tr>
                            <td class="number">b.</td>
                            <td class="title">NRIC</td>
                            <td class="colon">:</td>
                            <td>{{ optional($proposal->researcher)->nric }}</td>
                        </tr>
                        <tr>
                            <td class="number">c.</td>
                            <td class="title">Position</td>
                            <td class="colon">:</td>
                            <td>{{ optional(optional($proposal->researcher)->position)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number">d.</td>
                            <td class="title">Grade</td>
                            <td class="colon">:</td>
                            <td>{{ $proposal->grade }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="space">&nbsp;</td>
            </tr>

            <!-- item 3 working address -->
            <tr>
                <td class="number">3.</td>
                <td class="title">Working Address</td>
                <td class="colon">:</td>
                <td>{{ $proposal->working_address }}</td>
            </tr>

            <tr>
                <td class="number">4.</td>
                <td class="title">Tel. No.</td>
                <td class="colon">:</td>
                <td>{{ optional($proposal->researcher)->tel_no }}</td>
            </tr>
            
            <tr>
                <td class="number">5.</td>
                <td class="title">Fax No.</td>
                <td class="colon">:</td>
                <td>{{ optional($proposal->researcher)->fax_no }}</td>
            </tr>
            
            <tr>
                <td class="number">6.</td>
                <td class="title">Email</td>
                <td class="colon">:</td>
                <td>{{ optional($proposal->researcher)->email }}</td>
            </tr>

            <tr>
                <td class="number">7.</td>
                <td class="title">Keywords</td>
                <td class="colon">:</td>
                <td>{{ implode(", ", $proposal->keywords ?? []) }}</td>
            </tr>
        </table>
    </section>
</div>