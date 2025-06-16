<?php

namespace App\Http\Requests\ApplicationManagement;

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
        return [
            'project_number' => ['required', 'string'],
            'ptj_code' => ['required', 'array'],

            'schedule_start_date' => ['required', 'date_format:Y-m'],
            'schedule_duration' => ['required', 'numeric'],
        ];
    }
}
