<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefDivision;
use App\Models\RefStatusProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectStatusRequest extends FormRequest
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
                Rule::unique(RefStatusProject::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->status)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefStatusProject::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->status)->id)
            ],
        ];
    }
}
