<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefDivision;
use App\Models\RefPslkm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PslkmRequest extends FormRequest
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
                Rule::unique(RefDivision::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->division)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefDivision::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->division)->id)
            ],
            'status' => [
                'required',
                Rule::in(array_keys(RefPslkm::ARR_STATUS))
            ],
        ];
    }
}
