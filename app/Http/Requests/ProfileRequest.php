<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
        return array_merge([
            'name' => 'required',
            'nric' => 'nullable',
            'salutation' => 'nullable',
            'qualification' => 'nullable',
            'ref_division_id' => ['nullable', Rule::exists('ref_division', 'id')],
            'ref_position_id' => ['nullable', Rule::exists('ref_position', 'id')],
            'working_address' => ['nullable', 'string', 'max:255'],
            'file_picture' => ['nullable', 'file', 'image', 'max:1024'],
            'old_picture' => ['nullable', 'string'],
            'researcher_id' => ['nullable']
        ]);
    }
}
