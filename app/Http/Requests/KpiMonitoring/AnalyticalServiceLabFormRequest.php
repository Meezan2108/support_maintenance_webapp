<?php

namespace App\Http\Requests\KpiMonitoring;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AnalyticalServiceLabFormRequest extends FormRequest
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
            'year' => ['required', 'date_format:Y'],
            'quarter' => ['required', 'integer', 'min:1', 'max:4'],
            'no_sample' => ['required', 'integer'],
            'no_analysis' => ['required', 'integer'],
            'no_analysis_protocol' => ['required', 'integer'],

            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],

            "is_submited" => ['nullable']
        ];
    }
}
