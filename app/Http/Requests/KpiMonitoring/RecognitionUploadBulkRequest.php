<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\Proposal;
use App\Models\Recognition;
use App\Models\RefPubType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RecognitionUploadBulkRequest extends FormRequest
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
            'file_data.*.event' => ['required', 'string'],
            'file_data.*.recognition' => ['nullable', 'string'],
            'file_data.*.project_leader' => ['required', Rule::exists(User::class, 'email')],
            'file_data.*.team_member' => ['nullable', 'string'],
            'file_data.*.type' => ['required', Rule::in(array_values(Recognition::ARR_TYPE))],
            'file_data.*.project_number' => ['nullable', Rule::exists(Proposal::class, 'project_number')]
        ];
    }

    public function messages()
    {
        return [
            'file_data.*.date.required' => 'The date is required.',
            'file_data.*.date.date_format' => 'The date must formated as YYYY-MM-DD, but the given value is :input.',

            'file_data.*.event.required' => 'The publication title is required.',
            'file_data.*.recognition.required' => 'The publication title is required.',

            'file_data.*.project_leader.required' => 'The author is required.',
            'file_data.*.project_leader.exists' => 'The author ":input" is no found.',

            'file_data.*.type.required' => 'The type value is required.',
            'file_data.*.type.in' => 'The given type value ":input" is invalid.',

            'file_data.*.project_number.exists' => 'The given project number ":input" is not found.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'file_data' => collect($this->file_data)->map(function ($item) {
                $item["type"] = ucfirst(strtolower($item["type"]));
                return $item;
            })->toArray()
        ]);
    }
}
