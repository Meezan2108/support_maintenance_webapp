<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreatePublicationBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\PublicationUploadBulkRequest;
use App\Models\RefPubType;
use App\Models\User;
use App\Policies\PublicationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PublicationsUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(PublicationPolicy $policy)
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
                "url" => route("panel.publications.index"),
                "label" => "Publications",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Publications | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Publications",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => [
                    "no",
                    "date",
                    "author",
                    "co_author",
                    "publication",
                    "type",
                    "publisher",
                    "project_number",
                ],
                "publicationTypes" => RefPubType::all(),
                "urlTemplate" => url("/assets/template/01-publication.xlsx"),
                "urlSubmit" => route("panel.publications.bulk-store"),
                "urlIndex" => route("panel.publications.index")
            ]
        ]);
    }

    public function store(PublicationUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            // check
            $arrCoAuthor = [];
            foreach ($arrData["file_data"] as $data) {
                if (!($data["co_author"] ?? false)) {
                    continue;
                }

                $arrEmail = explode(",", $data["co_author"]);
                foreach ($arrEmail as $item) {
                    $arrCoAuthor[] = trim($item);
                }
            }

            $arrCoAuthor = array_unique($arrCoAuthor);
            $arrGetEmail = User::whereIn("email", $arrCoAuthor)
                ->pluck("email")
                ->toArray();
            $result = array_diff($arrCoAuthor, $arrGetEmail);
            if (count($result) > 0) {
                throw ValidationException::withMessages([
                    "co_author" => 'User email not found: ' . implode(", ", $result)
                ]);
            }

            (new CreatePublicationBulkData)->execute($arrData["file_data"] ?? [], $userAuth);

        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.publications.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Publications Success!"
            ]);
    }

}
