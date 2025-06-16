<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use App\Models\RefProposalBenefitsItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form6Request extends FormRequest
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

        // $requiredValue = $projectLeaderType == Proposal::TYPE_LEADER_INTERNAL
        //     ? 'required' : 'nullable';

        $requiredValue = 'nullable';

        return [
            'economic_contributions' => [$requiredValue, 'array'],
            'output_expected' => [$requiredValue, 'array'],
            'human_capital' => [$requiredValue, 'array'],

            'output_expected.*.ref_proposal_benefits_category_id' => ['required', Rule::exists(RefProposalBenefitsItem::class, 'id')->where('category', 1)],
            'output_expected.*.detail' => [$requiredValue, 'string'],
            'output_expected.*.quantity' => [$requiredValue, 'numeric'],

            'human_capital.*.ref_proposal_benefits_category_id' => ['required', Rule::exists(RefProposalBenefitsItem::class, 'id')->where('category', 2)],
            'human_capital.*.detail' => [$requiredValue, 'string'],
            'human_capital.*.quantity' => [$requiredValue, 'numeric'],
        ];
    }
}
