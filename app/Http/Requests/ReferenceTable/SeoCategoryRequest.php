<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefSeoCategory;
use App\Models\RefSeoSector;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeoCategoryRequest extends FormRequest
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
        $sector = new RefSeoSector();

        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefSeoCategory::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->category)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefSeoCategory::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->category)->id)
            ],
            "ref_seo_sector_id" => ['nullable', Rule::exists($sector->getTable(), 'id')]
        ];
    }
}
