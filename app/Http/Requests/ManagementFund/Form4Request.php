<?php

namespace App\Http\Requests\ManagementFund;

use App\Models\Proposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Form4Request extends FormRequest
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
            'research_methodology' => [$requiredValue],
            'risk_factor' => [$requiredValue],
            'risk_technical' => [$requiredValue],
            'risk_budget' => [$requiredValue],
            'risk_timing' => [$requiredValue],

            'schedule_start_date' => [$requiredValue, 'date_format:Y-m'],
            'schedule_duration' => [$requiredValue, 'numeric'],

            'activities' => [$requiredValue, 'array'],

            'activities.*.activities' => ["required", "string"],
            'activities.*.from' => ["required", 'date_format:Y-m'],
            'activities.*.to' => ["required", 'date_format:Y-m'],

            'milestones' => [$requiredValue, 'array'],

            'milestones.*.activities' => ["required", "string"],
            'milestones.*.from' => ["required", 'date_format:Y-m-d'],
        ];
    }
}
