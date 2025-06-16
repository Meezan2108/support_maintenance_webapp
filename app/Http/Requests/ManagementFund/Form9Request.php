<?php

namespace App\Http\Requests\ManagementFund;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Form9Request extends FormRequest
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
            'years' => ['required', 'array'],
            'cost_salaried.years' => ['nullable', 'array'],

            'cost_salaried.years.*' => ['numeric'],
            'save_as_draft' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'cost_salaried.years.*.numeric' => 'The expenses in each year must be a number.',
        ];
    }
}
