<?php

namespace App\Http\Requests\ReferenceTable;

use App\Models\RefDivision;
use App\Models\RefPslkm;
use App\Models\RefPslkmSub;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PslkmSubRequest extends FormRequest
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
                Rule::unique(RefPslkmSub::class, 'code')
                    ->whereNull('deleted_at')
                    ->where("status", RefPslkmSub::STATUS_ACTIVE)
                    ->ignore(optional($this->sub)->id)
            ],
            'ref_pslkm_id' => [
                'required', Rule::exists(RefPslkm::class, 'id')
            ],
            'description' => [
                'required', 'string',
                Rule::unique(RefPslkmSub::class, 'description')
                    ->whereNull('deleted_at')
                    ->where("status", RefPslkmSub::STATUS_ACTIVE)
                    ->ignore(optional($this->sub)->id)
            ],
            'status' => [
                'required',
                Rule::in(array_keys(RefPslkmSub::ARR_STATUS))
            ],
        ];
    }
}
