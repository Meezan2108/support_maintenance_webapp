<?php

namespace App\Http\Requests;

use App\Actions\User\GetUsersDatatables;
use App\Helpers\DatatablesHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserCredentialsRequest extends FormRequest
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
            'email' => [
                'required',
                Rule::unique('users', 'email')
                    ->ignore(optional($this->user)->id)
            ],
            'password' => ['nullable', 'min:8', 'confirmed']
        ];
    }
}
