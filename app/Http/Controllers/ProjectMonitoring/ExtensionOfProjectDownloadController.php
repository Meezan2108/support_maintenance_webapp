<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\ProposalMilestone;
use App\Models\User;
use App\Policies\ExtensionProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Str;


class ExtensionOfProjectDownloadController extends Controller
{

    protected $policy;

    public function __construct(ExtensionProjectPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function show(Request $request, ExtensionProject $application)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $application)) abort(403);


        $proposal = $application->proposal;

        $extensionProjects = $proposal->extensionProject()
            ->with("granttchart")
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->where("id", "<", $application->id)
            ->get();

        $report = $application->load(["proposal.milestones" => function ($query) {
            return $query->where("type", ProposalMilestone::TYPE_PROPOSAL);
        }, "proposal.researcher", "granttchart"]);

        $currentDuration = $extensionProjects->sum("duration") ?? 0;

        $actualCompletionDate = DateHelper::calcCompletionDate($proposal->schedule_start_date, $proposal->schedule_duration + $currentDuration);

        $pdf1 = Pdf::loadView("download-pdf.project-monitoring.extension-project.monitoring", [
            "report" => $report,
            "otherMilestones" => $extensionProjects->map(function ($item) {
                return $item->granttchart;
            })->flatten(),
            "actualCompletionDate" => $actualCompletionDate,
        ])->setOption(['dpi' => 110]);

        $proposal = $report->proposal;
        $pdf2 = Pdf::loadView("download-pdf.project-monitoring.extension-project.monitoring-p2-landscape", [
            "report" => $report,
            "otherMilestones" => $extensionProjects->map(function ($item) {
                return $item->granttchart;
            })->flatten(),
            "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration + $currentDuration + $report->duration)
        ])->setOption(['dpi' => 110])
            ->setPaper('A4', 'landscape');

        $m = new Merger();

        $m->addRaw($pdf1->output());
        $m->addRaw($pdf2->output());

        $nama_file = Str::slug('extensionproject-' . $report->proposal->project_number, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
