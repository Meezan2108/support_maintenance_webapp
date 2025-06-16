<?php

namespace App\Http\Requests\ProjectMonitoring\Mar;

use App\Models\Proposal;
use App\Models\ProposalMilestone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'proposal_id' => ['required', Rule::exists(Proposal::class, 'id')],
            'year_quarter' => ['required'],
            'year' => ['required', 'numeric'],
            'quarter' => ['required', 'numeric'],
            'milestones' => ['nullable', 'array'],

            'milestones.*.id' => ['required', Rule::exists(ProposalMilestone::class, 'id')],
            'milestones.*.is_achieved' => ['nullable', Rule::in([0, 1])],
            'milestones.*.completion_date' => ['nullable', 'date_format:Y-m'],

            'reason_not_achieved' => ['nullable'],
            'corrective_action' => ['nullable'],
            'revised_completion_date' => ['nullable', 'date'],

            'request_extension' => ['nullable', 'numeric'],
            'new_completion_date' => ['nullable', 'date'],
            'reason_for_extension' => ['nullable'],
        ];
    }
}
