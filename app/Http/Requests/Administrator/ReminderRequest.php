<?php

namespace App\Http\Requests\Administrator;

use App\Models\RefReminderCategory;
use App\Models\Reminder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReminderRequest extends FormRequest
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

        $selectedType = Reminder::ARR_REPEAT_TYPE[$this->repeat_type] ?? false;

        $arrAdd = [];
        if ($selectedType) {
            foreach ($selectedType["options"] as $item) {
                $arrOption = explode(":", $item);

                $key = "options." . $arrOption[0];
                $max = isset($arrOption[1]) ? "max:" . $arrOption[1] : '';

                $arrRule = ["nullable", "numeric", "min:1", $max];

                $arrAdd[$key] = $arrRule;
            }
        }

        if ($this->is_manual == 1) {
            $arrAdd['scheduled_at'] = ['nullable', 'date', 'after:now'];
        }
        return array_merge([

            'is_manual' => ['required', 'in:0,1'],
            'ref_reminder_category_id' => ['required', Rule::exists(RefReminderCategory::class, 'id')],
            'is_now' => ['nullable', 'in:0,1'],
            'repeat_type' => ['nullable', Rule::in(array_keys(Reminder::ARR_REPEAT_TYPE))],
            'notes' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],

            'status' => ['nullable', Rule::in(1, 0, true, false)],
        ], $arrAdd);
    }
}
