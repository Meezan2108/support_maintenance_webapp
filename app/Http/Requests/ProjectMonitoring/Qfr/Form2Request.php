<?php

namespace App\Http\Requests\ProjectMonitoring\Qfr;

use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\RefProjectCostSeries;
use App\Models\ReportQfDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form2Request extends FormRequest
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
            'total_recieved' => ['required', 'numeric'],
            'total_expenditure' => ['required', 'numeric'],
            'actual_project_expenditure' => ['nullable', 'array'],
            'is_inline_plan' => ['nullable', Rule::in([0, 1])],

            'actual_project_expenditure.*.id' => ['nullable', Rule::exists(ReportQfDetail::class, 'id')],
            'actual_project_expenditure.*.ref_project_cost_series_id' => ['required', Rule::exists(RefProjectCostSeries::class, 'id')],
            'actual_project_expenditure.*.total_approved' => ['nullable', 'numeric'],
            'actual_project_expenditure.*.total_recieved' => ['nullable', 'numeric'],
            'actual_project_expenditure.*.total_expenditure' => ['nullable', 'numeric']
        ];
    }

    public function attributes()
    {
        return [
            'total_recieved' => 'total received',
        ];
    }
}
