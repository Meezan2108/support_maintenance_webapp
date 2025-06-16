<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefSeoArea;
use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeoAreaRequest extends FormRequest
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
        $group = new RefSeoGroup();

        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefSeoArea::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->area)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefSeoArea::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->area)->id)
            ],
            "ref_seo_group_id" => ['nullable', Rule::exists($group->getTable(), 'id')]
        ];
    }
}
