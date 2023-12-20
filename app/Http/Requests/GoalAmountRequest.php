<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalAmountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'goal_amount' => 'numeric|min:1|max:999999',
        ];
    }
}
