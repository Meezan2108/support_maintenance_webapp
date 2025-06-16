<?php

namespace App\Http\Requests;

use App\Actions\User\GetUsersDatatables;
use App\Helpers\DatatablesHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $arrCreds = [];
        if (!$this->user) {
            $arrCreds = [
                'email' => ['required', Rule::unique('users', 'email')->whereNull('deleted_at')],
                'password' => ['required', 'min:8', 'confirmed']
            ];
        }

        return array_merge([
            'staf_id' => [
                'required',
                Rule::unique('users', 'staf_id')
                    ->ignore(optional($this->user)->id)
            ],
            'salutation' => 'nullable',
            'name' => 'required',
            'qualification' => 'nullable',
            'ref_division_id' => ['nullable', Rule::exists('ref_division', 'id')],
            'ref_position_id' => ['nullable', Rule::exists('ref_position', 'id')],
            'working_address' => ['nullable', 'string', 'max:255'],
            'roles' => 'required|array',
            'status' => ['required', Rule::in(1, 0, true, false)],
            'file_picture' => ['nullable', 'file', 'image', 'max:1024'],
            'old_picture' => ['nullable', 'string'],
            'researcher_id' => ['nullable']
        ], $arrCreds);
    }
}
