<?php

namespace App\Http\Controllers\ApplicationManagement\SubForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\Form4Request;
use App\Models\Fileable;
use App\Models\Proposal;
use App\Policies\ListOfApprovedPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Form4DocumentationController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function update(Form4Request $request, Proposal $form4)
    {
        $arrData = $request->validated();

        $proposal = $form4;

        if (!$this->policy->revision($request->user(), $proposal)) {
            abort(403);
        }

        $proposal = DB::transaction(function () use ($proposal, $request, $arrData) {

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $proposal->fileable()->create(array_merge([
                        "code_type" => Proposal::FILEABLE_DOCUMENTATION_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
            $proposal->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            return $proposal;
        });

        return redirect()->back();
    }
}
