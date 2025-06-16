<?php

namespace App\Providers;

use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\ExtensionProject;
use App\Models\Granttchartable;
use App\Models\ImportedGermplasm;
use App\Models\IPR;
use App\Models\KpiAchievement;
use App\Models\MilestoneCommercialization;
use App\Models\MilestoneExpertiseDevelopment;
use App\Models\MilestoneIpr;
use App\Models\MilestonePrototype;
use App\Models\MilestonePublication;
use App\Models\OutputRnd;
use App\Models\Proposal;
use App\Models\ProposalBenefits;
use App\Models\ProposalCollaboration;
use App\Models\ProposalEconomicContribution;
use App\Models\ProposalMilestone;
use App\Models\ProposalObjectives;
use App\Models\ProposalProjectActivities;
use App\Models\ProposalProjectCost;
use App\Models\ProposalProjectCostDetail;
use App\Models\ProposalProjectTeam;
use App\Models\ProposalResearcher;
use App\Models\ProposalResearchField;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\ReportEndProject;
use App\Models\ReportMilestone;
use App\Models\ReportQfDetail;
use App\Models\ReportQuarterly;
use App\Models\ReportQuarterlyFinancial;
use App\Models\ReportResearchProgress;
use App\Models\TargetKpi;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use App\Services\Auth\Contracts\AuthService;
use App\Services\Auth\CrimsAuthByIdService;
use App\Services\Auth\MockAuthService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        JsonResource::withoutWrapping();

        if (config('services.lkm.use_mock')) {
            $this->app->bind(AuthService::class, MockAuthService::class);
        } else {
            $this->app->bind(AuthService::class, CrimsAuthByIdService::class);
        }

        // KPI Monitoring Observer
        KpiAchievement::observe(ActivityLogObserver::class);
        Publication::observe(ActivityLogObserver::class);
        Recognition::observe(ActivityLogObserver::class);
        OutputRnd::observe(ActivityLogObserver::class);
        IPR::observe(ActivityLogObserver::class);
        Commercialization::observe(ActivityLogObserver::class);
        AnalyticalServiceLab::observe(ActivityLogObserver::class);
        ImportedGermplasm::observe(ActivityLogObserver::class);
        TargetKpi::observe(ActivityLogObserver::class);

        // Proposal Observer
        Proposal::observe(ActivityLogObserver::class);
        ProposalResearcher::observe(ActivityLogObserver::class);
        ProposalObjectives::observe(ActivityLogObserver::class);
        ProposalResearchField::observe(ActivityLogObserver::class);
        ProposalProjectActivities::observe(ActivityLogObserver::class);
        ProposalMilestone::observe(ActivityLogObserver::class);
        ProposalBenefits::observe(ActivityLogObserver::class);
        ProposalEconomicContribution::observe(ActivityLogObserver::class);
        ProposalCollaboration::observe(ActivityLogObserver::class);
        ProposalProjectTeam::observe(ActivityLogObserver::class);
        ProposalProjectCost::observe(ActivityLogObserver::class);
        ProposalProjectCostDetail::observe(ActivityLogObserver::class);

        // Project Monitoring Observer (MAR)
        ReportQuarterly::observe(ActivityLogObserver::class);
        ReportMilestone::observe(ActivityLogObserver::class);
        MilestoneIpr::observe(ActivityLogObserver::class);
        MilestonePublication::observe(ActivityLogObserver::class);
        MilestoneExpertiseDevelopment::observe(ActivityLogObserver::class);
        MilestonePrototype::observe(ActivityLogObserver::class);
        MilestoneCommercialization::observe(ActivityLogObserver::class);

        // Project Monitoring Observer (QFR)
        ReportQuarterlyFinancial::observe(ActivityLogObserver::class);
        ReportQfDetail::observe(ActivityLogObserver::class);

        // Project Monitoring Observer Extension of Project
        ExtensionProject::observe(ActivityLogObserver::class);
        Granttchartable::observe(ActivityLogObserver::class);

        // Project Monitoring Observer End of Project
        ReportEndProject::observe(ActivityLogObserver::class);

        // Project Monitoring Observer Research Progress
        ReportResearchProgress::observe(ActivityLogObserver::class);

        // User
        User::observe(ActivityLogObserver::class);

        Approvement::observe(ActivityLogObserver::class);

        Inertia::version(function () {
        return md5_file(public_path('mix-manifest.json')); // or vite.config.js
        });

        Inertia::share([
        'flash' => function () {
            return [
                'success' => Session::get('success'),
                'error' => Session::get('error'),
                // add other flash types if needed
            ];
        },
    ]);
    }
}
