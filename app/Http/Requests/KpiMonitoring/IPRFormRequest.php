<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPatent;
use App\Models\RefPubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IPRFormRequest extends FormRequest
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
            'output' => ['required'],

            'ref_patent_id' => ['required', Rule::exists(RefPatent::class, 'id')],
            'proposal_id' => ['nullable'],

            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],

            "is_submited" => ['nullable']
        ];
    }
}
