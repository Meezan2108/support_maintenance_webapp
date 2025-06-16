<style>
    
</style>

<div id="s1-project-identification">
    <section>
        <div class="mb-1">
            <h2 class="title-1"><span class="number">B.</span> Objectives of The Project</h2>
        </div>

    </section>
    <section>
        <table class="table-noborder line-height-3 text-bold">
            <tr>
                <td class="number">1.</td>
                <td class="title">Objectives</td>
                <td class="colon">:</td>
                <td></td>
            </tr>
        </table>

        @foreach($proposal->objectives as $objective)
            <div class="margin-tab1 text-justified">
            {!! $objective->description !!}
            </div>
        @endforeach
    
            
        <table class="table-noborder line-height-3 text-bold"> 
            <tr>
                <td class="number" width="30pt">2.</td>
                <td class="title" width="125pt">Type Of Research</td>
                <td class="colon" width="5pt">:</td>
                <td>{{ optional($proposal->researchType)->description }}</td>
            </tr>

            <tr>
                <td class="number">3.</td>
                <td colspan="3">Research Cluster and SEO Categories being addressed by the Project</td>
            </tr>
        </table>
        
        <table class="table-noborder line-height-3 text-bold margin-tab1">
            <tr>
                <td class="number">a.</td>
                <td class="title">Research Cluster</td>
                <td class="colon">:</td>
                <td>{{ optional($proposal->researchCluster)->description }}</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td colspan="3">
                    <table class="sub-table">
                        <tr>
                            <td class="number">i.</td>
                            <td class="title">SEO Category</td>
                            <td class="colon">:</td>
                            <td>{{ optional($proposal->seoCategory)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number">ii.</td>
                            <td class="title">SEO Group</td>
                            <td class="colon">:</td>
                            <td>{{ optional($proposal->seoGroup)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number">iii.</td>
                            <td class="title">SEO Area</td>
                            <td class="colon">:</td>
                            <td>{{ optional($proposal->seoArea)->description }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="number">b.</td>
                <td class="title">Field of Research</td>
                <td class="colon">:</td>
                <td>{{ optional($proposal->researchCluster)->description }}</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td colspan="3">
                    <table class="sub-table">
                        <tr>
                            <td class="number">i.</td>
                            <td colspan="3">Primary Search of Research</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Category</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->primaryResearchField)->category)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Group</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->primaryResearchField)->group)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Area</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->primaryResearchField)->area)->description }}</td>
                        </tr>

                        <tr>
                            <td class="number">ii.</td>
                            <td colspan="3">Secondary Search of Research</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Category</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->secondaryResearchField)->category)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Group</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->secondaryResearchField)->group)->description }}</td>
                        </tr>
                        <tr>
                            <td class="number"></td>
                            <td class="title">FOR Area</td>
                            <td class="colon">:</td>
                            <td >{{ optional(optional($proposal->secondaryResearchField)->area)->description }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        </table>
    </section>
</div>