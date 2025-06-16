<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\Proposal;
use App\Models\RefPslkm;
use App\Models\RefPslkmSub;
use App\Models\RefReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResearchProgressFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year' => ['required', 'numeric'],
            'proposal_id' => ['required', Rule::exists(Proposal::class, 'id')],
            'ref_report_type_id' => ['required', Rule::exists(RefReportType::class, 'id')],
            'ref_pslkm_id' => [
                'required',
                Rule::exists(RefPslkm::class, 'id')
                    ->where("status", RefPslkm::STATUS_ACTIVE)
            ],
            'ref_pslkm_sub_id' => [
                'required',
                Rule::exists(RefPslkmSub::class, 'id')
                    ->where("status", RefPslkmSub::STATUS_ACTIVE)
            ],
            'focus_area' => 'nullable',
            'issue' => ['nullable'],
            'strategy' => ['nullable'],
            'program' => ['nullable'],
            'date' => ['required', 'date_format:Y-m-d'],

            'background' => ['nullable'],
            'result' => ['nullable'],
            'summary' => ['nullable'],

            'is_submited' => ['nullable', Rule::in([0, 1])],

            'old_files' => ['nullable', 'array']
        ];
    }
}
