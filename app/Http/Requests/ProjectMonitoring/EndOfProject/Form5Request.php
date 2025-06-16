<?php

namespace App\Http\Requests\ProjectMonitoring\EndOfProject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form5Request extends FormRequest
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
            'asses_research' => ['nullable'],
            'asses_schedule' => ['nullable'],
            'asses_cost' => ['nullable'],
        ];
    }
}
