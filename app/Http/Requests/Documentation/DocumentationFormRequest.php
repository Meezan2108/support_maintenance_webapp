<?php

namespace App\Http\Requests\Documentation;

use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\RefPubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentationFormRequest extends FormRequest
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
            'submission_date' => ['required', 'date_format:Y-m-d'],
            'description' => ['required'],
            'category' => ['required'],
            'new_files' => ['nullable', 'array'],
            'new_files.*' => ['file', 'max:102400'],
            'old_files' => ['nullable', 'array'],
            "is_submited" => ['nullable']
        ];
    }
}
