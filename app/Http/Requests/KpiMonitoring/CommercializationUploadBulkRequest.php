<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommercializationUploadBulkRequest extends FormRequest
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
            'file_data' => ['required', 'array'],
            'file_data.*.date' => ['required', 'date_format:Y-m-d'],
            'file_data.*.project_leader' => ['required', Rule::exists(User::class, 'email')],
            'file_data.*.name' => ['required', 'string'],
            'file_data.*.taker' => ['required', 'string'],
            'file_data.*.category' => ['required', Rule::exists(RefOutputType::class, "description")],
            'file_data.*.project_number' => ['nullable', Rule::exists(Proposal::class, 'project_number')]
        ];
    }

    public function messages()
    {
        return [
            'file_data.*.date.required' => 'The date is required.',
            'file_data.*.date.date_format' => 'The date must formated as YYYY-MM-DD, but the given value is :input.',

            'file_data.*.name.required' => 'The commercialization name is required.',
            'file_data.*.taker.required' => 'The commercialization taker is required.',

            'file_data.*.project_leader.required' => 'The project leader is required.',
            'file_data.*.project_leader.exists' => 'The project leader ":input" is no found.',

            'file_data.*.category.required' => 'The category value is required.',
            'file_data.*.category.exists' => 'The given category value ":input" is invalid.',

            'file_data.*.project_number.exists' => 'The given project number ":input" is not found.',
        ];
    }
}
