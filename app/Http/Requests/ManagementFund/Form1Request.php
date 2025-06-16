<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\RefTypeOfFunding;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form1Request extends FormRequest
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
            'project_leader_type' => ['required', Rule::in([1, 2])],
            'proposal_type' => [$requiredValue, Rule::in([1, 2])],
            'ref_type_of_funding_id' => ['required', Rule::exists(RefTypeOfFunding::class, 'id')],
            'project_title' => ['required', 'string'],
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'researcher.name' => ['required', 'string'],
            'researcher.nric' => ['required', 'string'],
            'researcher.ref_division_id' => [$requiredValue, Rule::exists(RefDivision::class, 'id')],
            'researcher.ref_position_id' => [$requiredValue, Rule::exists(RefPosition::class, 'id')],
            'researcher.tel_no' => ['required'],
            'researcher.fax_no' => ['nullable'],
            'researcher.email' => ['required', 'email'],
            'working_address' => [$requiredValue],
            'institution' => [$requiredValue],
            'grade' => [$requiredValue],
            'keywords' => [$requiredValue, 'array']
        ];
    }
}
