<?php

namespace App\Http\Requests\ReferenceTable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\RefForCategory;

class ForCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefForCategory::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->category)->id),
            ],
            'description' => 'nullable|string',
        ];
    }
}
