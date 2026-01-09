<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubmitPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'crypto_type' => 'required|in:BTC,USDT_ERC20,USDT_TRC20',
            'transaction_id' => 'required|string|max:255',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'Transaction ID is required.',
            'payment_proof.image' => 'Payment proof must be an image file.',
            'payment_proof.max' => 'Payment proof must not exceed 2MB.',
        ];
    }
}