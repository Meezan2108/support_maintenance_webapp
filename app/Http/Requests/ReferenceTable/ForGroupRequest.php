<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefForCategory;
use App\Models\RefForGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ForGroupRequest extends FormRequest
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
        $category = new RefForCategory();

        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefForGroup::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->group)->id)
            ],
            'description' => [
                'required', 'string',
            ],
            "ref_for_category_id" => ['nullable', Rule::exists($category->getTable(), 'id')]
        ];
    }
}
