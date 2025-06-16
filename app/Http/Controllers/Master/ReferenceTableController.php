<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReferenceTableController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(Request $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $arrRefLink = [
            [
                "link" => route('panel.ref-table.for-category.index'),
                "title" => "Location"
            ],
            // [
            //     "link" => route('panel.ref-table.for-group.index'),
            //     "title" => "Field Of Research (FOR) Group"
            // ],
            // [
            //     "link" => route('panel.ref-table.for-area.index'),
            //     "title" => "Field Of Research (FOR) Area"
            // ],
            // [
            //     "link" => route('panel.ref-table.seo-sector.index'),
            //     "title" => "SEO Sector"
            // ],
            // [
            //     "link" => route('panel.ref-table.seo-category.index'),
            //     "title" => "SEO Category"
            // ],
            // [
            //     "link" => route('panel.ref-table.seo-group.index'),
            //     "title" => "SEO Group"
            // ],
            // [
            //     "link" => route('panel.ref-table.seo-area.index'),
            //     "title" => "SEO Area"
            // ],
            // [
            //     "link" => route('panel.ref-table.research-cluster.index'),
            //     "title" => "Research Cluster"
            // ],
            // [
            //     "link" => route('panel.ref-table.research-type.index'),
            //     "title" => "Research Type"
            // ],
            // [
            //     "link" => route('panel.ref-table.patent.index'),
            //     "title" => "Intellectual Property"
            // ],
            // [
            //     "link" => route('panel.ref-table.other-document.index'),
            //     "title" => "Other Document"
            // ],
            // [
            //     "link" => route('panel.ref-table.division.index'),
            //     "title" => "Division"
            // ],
            // [
            //     "link" => route('panel.ref-table.position.index'),
            //     "title" => "Position"
            // ],
            // [
            //     "link" => route('panel.ref-table.project-status.index'),
            //     "title" => "Project Status"
            // ],
            // [
            //     "link" => route('panel.ref-table.pslkm.index'),
            //     "title" => "Pslkm"
            // ],
            // [
            //     "link" => route('panel.ref-table.pslkm-sub.index'),
            //     "title" => "Sub Pslkm"
            // ],
        ];

        return Inertia::render('Master/RefTable/Index', [
            "arrRefLink" => $arrRefLink
        ]);
    }
}
