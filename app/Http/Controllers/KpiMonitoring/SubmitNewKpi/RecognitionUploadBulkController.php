<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateRecognitionBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\RecognitionUploadBulkRequest;
use App\Models\User;
use App\Policies\RecognitionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RecognitionUploadBulkController extends Controller
{

    protected $policy;
    protected $createPublicationSubmitNotification;
    protected $createPublicationSubmitTask;

    public function __construct(RecognitionPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function create(Request $request)
    {
        if (!$this->policy->createBulk($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        $breadcrumbs = [
            [
                "url" => "#",
                "label" => "R&D LKM KPI Monitoring",
            ],
            [
                "url" => route("panel.recognition.index"),
                "label" => "Publications",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Recognition | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Recognition",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "date", "event", "recognition", "project_leader", "team_member", "type", "project_number"],
                "urlTemplate" => url("/assets/template/02-recognition.xlsx"),
                "urlSubmit" => route("panel.recognition.bulk-store"),
                "urlIndex" => route("panel.recognition.index")
            ]
        ]);
    }

    public function store(RecognitionUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            $teamMember = [];
            foreach ($arrData["file_data"] as $data) {
                if (!($data["team_member"] ?? false)) {
                    continue;
                }

                $arrEmail = explode(",", $data["team_member"]);
                foreach ($arrEmail as $item) {
                    $teamMember[] = trim($item);
                }
            }

            $teamMember = array_unique($teamMember);
            $arrUserEmail = User::whereIn("email", $teamMember)
                ->pluck("email")
                ->toArray();
            $notExistsEmail = array_diff($teamMember, $arrUserEmail);
            if (count($notExistsEmail) > 0) {
                throw ValidationException::withMessages([
                    "team_member" => 'User email not found: ' . implode(", ", $notExistsEmail)
                ]);
            }

            (new CreateRecognitionBulkData)->execute($arrData["file_data"] ?? [], $userAuth);

        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.recognition.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Recognition Success!"
            ]);
    }

}
