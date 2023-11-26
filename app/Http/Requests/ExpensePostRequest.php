<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'user_id' => 'required|exists:users,id', // "exists" のスペルが正しいか確認
            'category_id' => 'required|exists:expense_categories,id', // "exists" のスペルが正しいか確認
            'price' => 'required|numeric|min:1|max:99999999', // "numeric" ルールを使用して数値であることを確認
            'date' => 'required|date',
        ];
    }
}
