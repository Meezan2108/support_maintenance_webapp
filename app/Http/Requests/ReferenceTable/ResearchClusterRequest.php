<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefResearchCluster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResearchClusterRequest extends FormRequest
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
                Rule::unique(RefResearchCluster::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->cluster)->id)
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefResearchCluster::class, 'description')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->cluster)->id)
            ],
        ];
    }
}
