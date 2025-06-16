<?php

namespace App\Http\Requests\ProjectMonitoring\EndOfProject;

use App\Models\Objectiveable;
use App\Models\Proposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form3Request extends FormRequest
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
            'objectives_achieved' =>  ['nullable', 'array'],
            'objectives_achieved.*.id' =>  ['nullable', 'numeric', Rule::exists(Objectiveable::class, 'id')],
            'objectives_achieved.*.description' =>  ['required', 'string'],

            'objectives_not_achieved' =>  ['nullable', 'array'],
            'objectives_not_achieved.*.id' =>  ['nullable', 'numeric', Rule::exists(Objectiveable::class, 'id')],
            'objectives_not_achieved.*.description' =>  ['required', 'string'],
        ];
    }
}
