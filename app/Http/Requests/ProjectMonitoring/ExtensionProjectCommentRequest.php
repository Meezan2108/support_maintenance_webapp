<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\ReportQuarterly;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExtensionProjectCommentRequest extends FormRequest
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
            'comment' => 'nullable|string',
            'is_submited' => ['required', Rule::in([0, 1])],
            'status' => ['nullable', Rule::in(
                ReportQuarterly::STATUS_APPROVED,
                ReportQuarterly::STATUS_AMEND,
                ReportQuarterly::STATUS_REJECTED
            )]
        ];
    }
}
