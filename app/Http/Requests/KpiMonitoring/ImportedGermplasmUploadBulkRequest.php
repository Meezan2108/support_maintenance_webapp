<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ImportedGermplasmUploadBulkRequest extends FormRequest
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
            'file_data.*.project_leader' => ['required', Rule::exists(User::class, 'email')],
            'file_data.*.year' => ['required', 'numeric'],
            'file_data.*.quarter' => ['required', 'numeric', "between:1,4"],
            'file_data.*.no_of_germplasm' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'file_data.*.year.required' => 'The year value is required.',
            'file_data.*.year.numeric' => 'The year value must be a numeric.',

            'file_data.*.quarter.required' => 'The quarter value name is required.',
            'file_data.*.quarter.numeric' => 'The quarter value must be a numeric.',
            'file_data.*.quarter.between' => 'The quarter value is not valid.',

            'file_data.*.project_leader.required' => 'The project leader is required.',
            'file_data.*.project_leader.exists' => 'The project leader ":input" is no found.',

            'file_data.*.no_of_germplasm.required' => 'The category value is required.',
            'file_data.*.no_of_germplasm.numeric' => 'The no of sample value must be a numeric.',

        ];
    }
}
