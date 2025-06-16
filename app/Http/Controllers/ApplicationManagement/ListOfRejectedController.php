<?php

namespace App\Http\Controllers\ApplicationManagement;

use App\Actions\ApplicationManagement\ListOfProposalDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\EvaluationSearchRequest;
use App\Http\Resources\ApplicationManagement\EvaluationTableResource;
use App\Http\Resources\ApplicationManagement\ListOfProposalTableResource;
use App\Models\Proposal;
use App\Policies\EvaluationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ListOfRejectedController extends Controller
{

    protected $policy;

    public function __construct(EvaluationPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(ListOfProposalDatatables $datatables, EvaluationSearchRequest $request)
    {
        if (!$this->policy->viewAny(Auth::user())) abort(403);

        $filters = $request->validated();
        $data = $datatables
            ->setColumn(ListOfProposalDatatables::TYPE_REJECTED)
            ->execute($filters, Proposal::STATUS_REJECTED);

        $request->session()->put('filters', $filters);

        return Inertia::render('ApplicationManagement/ListOfRejected/Index', [
            "title" => "List of Rejected Project | Application Management",
            "additional" => [
                "data" => ListOfProposalTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "urlIndex" => route("panel.list-of-rejected.index")
            ]
        ]);
    }
}
