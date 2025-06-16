<?php

namespace Database\Seeders;

use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);


        $this->call(RefDivisionSeeder::class);
        $this->call(RefForAreaSeeder::class);
        $this->call(RefForCategorySeeder::class);
        $this->call(RefForGroupSeeder::class);

        $this->call(RefPatentSeeder::class);
        $this->call(RefOtherDocumentSeeder::class);
        $this->call(RefProjectCostSeriesSeeder::class);
        $this->call(RefPubTypeSeeder::class);
        $this->call(RefOutputTypeSeeder::class);
        $this->call(RefOutputStatusSeeder::class);
        $this->call(RefResearchClusterSeeder::class);
        $this->call(RefResearchTypeSeeder::class);

        // $this->call(refPTJSeeder::class);

        $this->call(RefSeoSectorSeeder::class);
        $this->call(RefSeoCategorySeeder::class);
        $this->call(RefSeoGroupSeeder::class);
        $this->call(RefSeoAreaSeeder::class);

        $this->call(RefStatusProjectSeeder::class);
        $this->call(RefStatusProposalSeeder::class);
        $this->call(RefTypeOfFundingSeeder::class);

        $this->call(RefProposalBenefitsItemSeeder::class);
        $this->call(RefEvaluationQuestionSeeder::class);

        $this->call(RefReportEopBenefitsSeeder::class);
        $this->call(RefPositionSeeder::class);

        $this->call(RefReportTypeSeeder::class);

        $this->call(TemplateSeeder::class);

        $this->call(RefTargetKpiCategorySeeder::class);
        $this->call(RefTargetKpiPeriodSeeder::class);
    }
}
