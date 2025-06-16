<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\ProjectTeamable;
use App\Models\Proposal;
use App\Models\RefPslkm;
use App\Models\RefReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResearchProgressNoFundFormRequest extends FormRequest
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
            'year' => ['nullable', 'numeric'],
            'proposal_id' => ['nullable', Rule::exists(Proposal::class, 'id')],
            'ref_report_type_id' => ['nullable', Rule::exists(RefReportType::class, 'id')],
            'ref_pslkm_id' => ['required', Rule::exists(RefPslkm::class, 'id')],
            'focus_area' => 'nullable',
            'issue' => ['nullable'],
            'strategy' => ['nullable'],
            'program' => ['nullable'],
            'date' => ['nullable', 'date_format:Y-m-d'],

            'project_title' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],

            'project_team' => ['nullable', 'array'],

            'project_team.*.id' => ['nullable', 'numeric'],
            'project_team.*.name' => ['required', 'string'],
            'project_team.*.type' => ['required', Rule::in([
                ProjectTeamable::TYPE_LEADER,
                ProjectTeamable::TYPE_RESEARCHER,
                ProjectTeamable::TYPE_STAFF
            ])],
            "project_team.*.organization" => ['nullable', 'string'],
            "project_team.*.man_month" => ['nullable', 'string'],

            'objectives' => ['nullable', 'array'],

            'objectives.description' => ['nullable', 'numeric'],
            'objectives.id' => ['nullable', 'string'],

            'summary' => ['nullable', 'string'],

            'is_submited' => ['nullable', Rule::in([0, 1])],

            'old_files' => ['nullable', 'array'],
            'new_files' => ['nullable', 'array'],
        ];
    }
}
