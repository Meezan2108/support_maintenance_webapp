<?php

namespace App\Http\Requests\KpiMonitoring;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TargetKpiGlobalFormRequest extends FormRequest
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
            'year' => ['required'],
            'category_id' => ['required'],
            'sub_category_id' => ['nullable'],
            'target' => ['required', 'integer'],
            "is_submited" => ['nullable']
        ];
    }
}
