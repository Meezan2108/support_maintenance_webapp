<?php

namespace App\Http\Requests\ProjectMonitoring\Qfr;

use App\Models\ReportQuarterly;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FormCommentRequest extends FormRequest
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
            'project_details' => 'nullable|string',
            'financial_progress' => 'nullable|string',
            'budget_variations' => 'nullable|string',
            'proposed_action' => 'nullable|string',
            'last' => 'required|boolean',
            'status' => $this->input('last') == true
                ? Rule::in(
                    ReportQuarterly::STATUS_APPROVED,
                    ReportQuarterly::STATUS_AMEND,
                    ReportQuarterly::STATUS_REJECTED
                )
                : "nullable"
        ];
    }
}
