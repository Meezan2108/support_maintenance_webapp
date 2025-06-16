<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\Proposal;
use App\Models\ProposalMilestone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExtensionProjectFormRequest extends FormRequest
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
            'justification' => ['nullable', 'string'],
            'new_fund' => ['nullable', 'string'],
            'duration' => ['required', 'numeric'],
            'balance_to_date' => ['nullable', 'numeric'],

            'milestones_extension' => ['nullable', 'array'],

            'milestones_extension.*.id' => ['nullable'],
            'milestones_extension.*.activities' => ['required', 'string'],
            'milestones_extension.*.from' => ['required', 'date_format:Y-m-d'],

            'is_submited' => ['nullable', Rule::in([0, 1])]
        ];
    }
}
