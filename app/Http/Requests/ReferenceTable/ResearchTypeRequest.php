<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefResearchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResearchTypeRequest extends FormRequest
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
            'code' => [
                'nullable', 'string',
                Rule::unique(RefResearchType::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->type)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefResearchType::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->type)->id)
            ],
        ];
    }
}
