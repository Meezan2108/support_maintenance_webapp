<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\Approvement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResearchProgressCommentRequest extends FormRequest
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
            'status' => [
                $this->input('is_submited') == 1 ? 'required' : 'nullable',
                Rule::in(
                    Approvement::STATUS_APPROVED,
                    Approvement::STATUS_AMEND,
                    Approvement::STATUS_REJECTED
                )
            ]
        ];
    }
}
