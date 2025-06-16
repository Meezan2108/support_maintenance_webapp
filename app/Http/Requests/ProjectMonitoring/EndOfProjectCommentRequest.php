<?php

namespace App\Http\Requests\ProjectMonitoring;

use App\Models\Approvement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EndOfProjectCommentRequest extends FormRequest
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

            "project_details" => "nullable",
            "objectives_project" => "nullable",
            "objectives_achievement" => "nullable",
            "technology" => "nullable",
            "assessment" => "nullable",
            "additional_funding" => "nullable",
            "benefits" => "nullable",
            "report" => "nullable",

            'status' => $this->input('last') == true
                ? Rule::in(
                    Approvement::STATUS_APPROVED,
                    Approvement::STATUS_AMEND,
                    Approvement::STATUS_REJECTED
                )
                : "nullable",

            "last" => "nullable|boolean",
            "is_submited" => "nullable"
        ];
    }
}
