<?php

namespace App\Http\Requests\ProjectMonitoring\Mar;

use App\Models\RefPubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form2Request extends FormRequest
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
            'ipr' =>  ['nullable', 'array'],
            'ipr.*.output' => ['required', 'string'],
            'ipr.*.date' => ['required', 'date_format:Y-m-d'],

            'publications' => ['nullable', 'array'],
            'publications.*.title' => ['required'],
            'publications.*.publisher' => ['required'],
            'publications.*.ref_pub_type_id' => ['required', Rule::exists(RefPubType::class, 'id')],
            'publications.*.date' => ['required', 'date_format:Y-m-d'],

            'expertise_development' => ['nullable', 'array'],
            'expertise_development.*.output' => ['required'],
            'expertise_development.*.date' => ['required', 'date_format:Y-m-d'],

            'prototype' => ['nullable', 'array'],
            'prototype.*.output' => ['required'],
            'prototype.*.date' => ['required', 'date_format:Y-m-d'],

            'commercialization' => ['nullable', 'array'],
            'commercialization.*.category' => ['required'],
            'commercialization.*.name' => ['required'],
            'commercialization.*.taker' => ['required'],
            'commercialization.*.date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
