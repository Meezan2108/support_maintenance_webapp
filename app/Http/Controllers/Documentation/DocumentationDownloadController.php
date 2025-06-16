<?php

namespace App\Http\Controllers\Documentation;

use App\Actions\Documentation\GetDocumentationDatatables;
use App\Actions\KpiMonitoring\GetOutputRnDDatatables;
use App\Actions\KpiMonitoring\GetPublicationsDatatables;
use App\Actions\KpiMonitoring\GetRecognitionDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Documentation\DocumentationFormRequest;
use App\Http\Requests\Documentation\DocumentationSearchRequest;
use App\Http\Requests\KpiMonitoring\OutputRnDFormRequest;
use App\Http\Requests\KpiMonitoring\OutputRnDSearchRequest;
use App\Http\Requests\KpiMonitoring\PublicationFormRequest;
use App\Http\Requests\KpiMonitoring\PublicationSearchRequest;
use App\Http\Requests\KpiMonitoring\RecognitionFormRequest;
use App\Http\Requests\KpiMonitoring\RecognitionSearchRequest;
use App\Http\Resources\Documentation\DocumentationResource;
use App\Http\Resources\FileableResource;
use App\Http\Resources\KpiMonitoring\OutputRndResource;
use App\Http\Resources\KpiMonitoring\PublicationResource;
use App\Http\Resources\KpiMonitoring\RecognitionResource;
use App\Models\Approvement;
use App\Models\Documentation;
use App\Models\Fileable;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefOtherDocument;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPubType;
use App\Policies\DocumentationPolicy;
use App\Policies\FileablePolicy;
use App\Policies\OutputRnDPolicy;
use App\Policies\PublicationPolicy;
use App\Policies\RecognitionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use League\CommonMark\Node\Block\Document;

class DocumentationDownloadController extends Controller
{

    protected $policy;

    public function __construct(FileablePolicy $policy)
    {
        $this->policy = $policy;
    }

    public function show(Request $request, Fileable $fileable)
    { 
        if (!$this->policy->view($request->user(), $fileable)) {
            abort(403);
        }

        return response($fileable->file)
            ->withHeaders([
                'Content-Type' => $fileable->file_type,
                'Cache-Control' => 'no-cache private',
                'Content-Disposition' => 'attachment; filename="' . $fileable->file_name,
                'Content-Transfer-Encoding' => 'binary'
            ]);
    }
}
