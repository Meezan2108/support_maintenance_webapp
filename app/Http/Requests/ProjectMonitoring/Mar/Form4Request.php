<?php

namespace App\Http\Requests\ProjectMonitoring\Mar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form4Request extends FormRequest
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
            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],
            'is_submited' => ['nullable', 'boolean']
        ];
    }
}
