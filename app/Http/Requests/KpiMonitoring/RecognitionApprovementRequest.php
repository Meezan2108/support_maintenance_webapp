<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\Approvement;
use App\Models\RefPubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RecognitionApprovementRequest extends FormRequest
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
            'approval_status' => ['required', Rule::in([
                Approvement::STATUS_APPROVED, Approvement::STATUS_AMEND, Approvement::STATUS_REJECTED
            ])],
        ];
    }
}
