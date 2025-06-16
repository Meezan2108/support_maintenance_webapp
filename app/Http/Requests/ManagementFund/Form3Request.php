<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Form3Request extends FormRequest
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
            'research_location' => [$requiredValue],
            'project_summary' => [$requiredValue],
            'problem_statement' => [$requiredValue],

            'hypothesis' => [$requiredValue],
            'research_question' => [$requiredValue],
            'literature_review' => [$requiredValue],

            'relevance_goverment_policy' => [$requiredValue],
            'reference' => [$requiredValue],
            'related_research' => [$requiredValue],
        ];
    }
}
