<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OldDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PeribadiOldDataSeeder::class);
        $this->call(ProposalOldDataSeeder::class);
        $this->call(ProjectOldDataSeeder::class);
        $this->call(ProposalProjectTeamOldDataSeeder::class);

        $this->call(ProposalProjectCostOldDataSeeder::class);
        $this->call(ProposalMilestoneOldDataSeeder::class);

        $this->call(ReportOldDataSeeder::class);
        $this->call(JSeriesTerimaOldDataSeeder::class);


        // KPI
        $this->call(IprOldDataSeeder::class);
        $this->call(OutputOldDataSeeder::class);
        $this->call(PublicationOldDataSeeder::class);
        $this->call(Publication2OldDataSeeder::class);
        $this->call(RecognitionOldDataSeeder::class);
        $this->call(RecognitionOldDataSeeder::class);

        $this->call(ProposalDocumentOldDataSeeder::class);
        $this->call(ProposalOtherDoc1OldDataSeeder::class);
        $this->call(ProposalOtherDoc2OldDataSeeder::class);
        $this->call(ProposalOtherDoc3OldDataSeeder::class);
        $this->call(ProposalOtherDoc4OldDataSeeder::class);
    }
}
