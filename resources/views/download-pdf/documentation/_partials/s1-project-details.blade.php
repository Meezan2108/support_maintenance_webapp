<style>
    
</style>

<div id="s1-project-identification" class="line-height-3 text-bold">
    <section>
        <div class="mb-4">
            <center><h2 class="title-1">Documentation</h2></center>
        </div>

    </section>

    <section>
        <table class="table-border" width="100%">
            <tr>
                <td>
                    <div class="mb-1">
                        <h5 class="title-1"><span class="number">A.</span> Documentation</h5>
                    </div>
                    <div>
                        <table class="sub-table-no-border">
                            <tr>
                                <td class="number">1.</td>
                                <td>Project Leader</td>
                                <td>:</td>
                                <td>{{ $documentation->projectLeader->name }}</td>
                            </tr>
                            <tr>
                                <td class="number">2.</td>
                                <td>Description</td>
                                <td>:</td>
                                <td>{{ $documentation->description }}</td>
                            </tr>
                            <tr>
                                <td class="number">3.</td>
                                <td>Category</td>
                                <td>:</td>
                                <td>{{ $documentation->Category->description }}</td>
                            </tr>
                            <tr>
                                <td class="number">3.</td>
                                <td>Submission of Date</td>
                                <td>:</td>
                                <td>{{ $documentation->submission_date }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</div>