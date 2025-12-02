<?php

namespace App\Modules\PaymentMethodsSunat\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodSunatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cod' => 'required|integer',
            'des' => 'required|string|max:540',
        ];
    }
}
