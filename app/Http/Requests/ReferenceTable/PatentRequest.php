<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefPatent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PatentRequest extends FormRequest
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
                Rule::unique(RefPatent::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->patent)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefPatent::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->patent)->id)
            ],
            'detail' => [
                'required', 'string',
                Rule::unique(RefPatent::class, 'detail')
                    ->ignore(optional($this->patent)->id)
            ],
        ];
    }
}
