<?php

namespace App\Http\Requests\KpiMonitoring;

use App\Actions\KpiMonitoring\GetMyKpiDatatables;
use App\Actions\KpiMonitoring\GetOutputRnDDatatables;
use App\Helpers\DatatablesHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MyKpiSearchRequest extends FormRequest
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
        $orderField = DatatablesHelper::getOrderAbleField((new GetMyKpiDatatables)->getColumns());

        return [
            'search_fields' => 'nullable|array',
            'search_values' => 'nullable|array',
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric',
            'order_by' => ['nullable', Rule::in($orderField)],
            'order_type' => 'nullable|in:asc,desc,ASC,DESC'
        ];
    }
}
