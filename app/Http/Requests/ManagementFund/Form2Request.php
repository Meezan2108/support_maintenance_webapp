<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use App\Models\RefForArea;
use App\Models\RefForCategory;
use App\Models\RefForGroup;
use App\Models\RefResearchCluster;
use App\Models\RefResearchType;
use App\Models\RefSeoArea;
use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Form2Request extends FormRequest
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
            'objectives' => [$requiredValue, 'array'],
            'ref_research_type_id' => [$requiredValue, Rule::exists(RefResearchType::class, 'id')],
            'ref_research_cluster_id' => [$requiredValue, Rule::exists(RefResearchCluster::class, 'id')],

            'ref_seo_category_id' => [$requiredValue, Rule::exists(RefSeoCategory::class, 'id')],
            'ref_seo_group_id' => [$requiredValue, Rule::exists(RefSeoGroup::class, 'id')],
            'ref_seo_area_id' => [$requiredValue, Rule::exists(RefSeoArea::class, 'id')],

            'for_primary.ref_for_category_id' => [$requiredValue, Rule::exists(RefForCategory::class, 'id')],
            'for_primary.ref_for_group_id' => [$requiredValue, Rule::exists(RefForGroup::class, 'id')],
            'for_primary.ref_for_area_id' => [$requiredValue, Rule::exists(RefForArea::class, 'id')],

            'for_secondary.ref_for_category_id' => [$requiredValue, Rule::exists(RefForCategory::class, 'id')],
            'for_secondary.ref_for_group_id' => [$requiredValue, Rule::exists(RefForGroup::class, 'id')],
            'for_secondary.ref_for_area_id' => [$requiredValue, Rule::exists(RefForArea::class, 'id')],
        ];
    }
}
