<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefForArea;
use App\Models\RefForGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ForAreaRequest extends FormRequest
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
        $group = new RefForGroup();

        return [
            'code' => [
                'nullable', 'string',
                Rule::unique(RefForArea::class, 'code')
                    ->whereNull('deleted_at')
                    ->ignore(optional($this->area)->id)
            ],
            'description' => [
                'required', 'string',
            ],
            "ref_for_group_id" => ['nullable', Rule::exists($group->getTable(), 'id')]
        ];
    }
}
