<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommercializationFormRequest extends FormRequest
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
            'date' => ['required', 'date_format:Y-m-d'],
            'name' => ['required'],

            "taker" => ['nullable'],
            'category' => ['nullable', Rule::exists(RefOutputType::class, 'id')],

            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],

            'proposal_id' => ['nullable'],
            "is_submited" => ['nullable']
        ];
    }
}
