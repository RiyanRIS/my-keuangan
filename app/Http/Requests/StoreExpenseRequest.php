<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wallet_id' => 'required|integer|exists:wallets,id',
            'category_id' => 'required|integer|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
            'transaction_date' => 'nullable|date',
            'transaction_time' => 'nullable|date_format:H:i',
        ];
    }
}
