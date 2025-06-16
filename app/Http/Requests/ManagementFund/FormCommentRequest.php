<?php

namespace App\Http\Requests\ManagementFund;

use App\Actions\ManagementFund\Trf\GetTrfDatatables;
use App\Actions\User\GetUsersDatatables;
use App\Helpers\DatatablesHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identification' => 'nullable|string',
            'objectives' => 'nullable|string',
            'research_background' => 'nullable|string',
            'research_approach' => 'nullable|string',
            'project_schedule' => 'nullable|string',
            'benefits' => 'nullable|string',
            'research_collabration' => 'nullable|string',
            'expenses_estimation' => 'nullable|string',
            'project_cost' => 'nullable|string',
        ];
    }
}
