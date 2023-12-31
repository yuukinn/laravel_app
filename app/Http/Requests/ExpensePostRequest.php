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
            'user_id' => 'required|exists:users,id', 
            'category_id' => 'required|exists:expense_categories,id', 
            'price' => 'required|numeric|min:1|max:99999999', 
            'category_detail'=> 'max:30',
            'date' => 'required|date',
            'asset_type' => 'required',
        ];
    }
}
