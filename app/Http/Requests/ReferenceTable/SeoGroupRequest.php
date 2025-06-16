<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeoGroupRequest extends FormRequest
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
        $category = new RefSeoCategory();

        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefSeoGroup::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->group)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefSeoGroup::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->group)->id)
            ],
            "ref_seo_category_id" => ['nullable', Rule::exists($category->getTable(), 'id')]
        ];
    }
}
