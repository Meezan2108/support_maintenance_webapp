<?php

namespace App\Http\Controllers\MonitoringFile;

use App\Http\Controllers\Controller;
use App\Actions\MonitoringFile\GetMonitoringFileDatatables;
use App\Http\Requests\MonitoringFile\MonitoringFileSearchRequest;
use App\Http\Resources\MonitoringFile\MonitoringFileResource;
use App\Policies\FileablePolicy;
use Inertia\Inertia;

class MonitoringFileController extends Controller
{

    protected $policy;

    public function __construct(FileablePolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetMonitoringFileDatatables $getfile, MonitoringFileSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $files = $getfile->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('MonitoringFile/Index', [
            "title" => "Monitoring File",
            "additional" => [
                "list" => MonitoringFileResource::collection($files),
                "filters" => $filters,
                "columns" => $getfile->getColumns(),

                "urlIndex" => route("panel.monitoring-file.index")
            ]
        ]);
    }
}
