<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Form8Request extends FormRequest
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
        $projectLeaderType = $this->get('project_leader_type');

        $requiredValue = $projectLeaderType == Proposal::TYPE_LEADER_INTERNAL
            ? 'required' : 'nullable';

        return [
            'years' => ['required', 'array'],
            'V21000' => ['nullable', 'array'],
            'V26000' => ['nullable', 'array'],
            'V28000' => ['nullable', 'array'],
            'V29000' => ['nullable', 'array'],

            'V21000.*.description' => [$requiredValue],
            'V21000.*.years' => [$requiredValue, 'array'],
            'V21000.*.years.*' => ['nullable', 'numeric'],

            'V26000.*.description' => [$requiredValue],
            'V26000.*.years' => [$requiredValue, 'array'],
            'V26000.*.years.*' => ['nullable', 'numeric'],

            'V28000.*.description' => [$requiredValue],
            'V28000.*.years' => [$requiredValue, 'array'],
            'V28000.*.years.*' => ['nullable', 'numeric'],

            'V29000.*.description' => [$requiredValue],
            'V29000.*.years' => [$requiredValue, 'array'],
            'V29000.*.years.*' => ['nullable', 'numeric'],

        ];
    }

    public function messages()
    {
        return [
            '*.*.years.*.numeric' => 'The expenses in each year must be a number.',
        ];
    }
}
