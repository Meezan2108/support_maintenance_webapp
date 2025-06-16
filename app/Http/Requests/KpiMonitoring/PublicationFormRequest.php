<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Models\RefPubType;
use App\Models\ResearcherInvolveable;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PublicationFormRequest extends FormRequest
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
            'date_published' => ['required', 'date_format:Y-m-d'],
            'title' => ['required'],

            'ref_pub_type_id' => ['nullable', Rule::exists(RefPubType::class, 'id')],
            'publisher' => ['nullable'],
            'proposal_id' => ['nullable'],
            "is_submited" => ['nullable'],

            'new_files' => ['nullable', 'array'],
            'old_files' => ['nullable', 'array'],

            "researchers" => ['nullable', 'array'],
            "researchers.*.user_id" => ['required', Rule::exists(User::class, 'id')->whereNull('deleted_at')],
            "researchers.*.name" => ['required', 'string'],
        ];
    }
}
