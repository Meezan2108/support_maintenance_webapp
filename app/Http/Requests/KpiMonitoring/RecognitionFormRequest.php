<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RecognitionFormRequest extends FormRequest
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
            'date' => ['required', 'date_format:Y-m-d'],
            'recognition' => ['required'],
            'type' => ['required'],
            'project' => ['nullable'],
            'proposal_id' => ['nullable'],
            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],
            "is_submited" => ['nullable'],

            "researchers" => ['nullable', 'array'],
            "researchers.*.user_id" => ['required', Rule::exists(User::class, 'id')->whereNull('deleted_at')],
            "researchers.*.name" => ['required', 'string'],
        ];
    }
}
