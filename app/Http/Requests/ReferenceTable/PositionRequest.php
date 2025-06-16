<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefPosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
                Rule::unique(RefPosition::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->position)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefPosition::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->position)->id)
            ],
        ];
    }
}
