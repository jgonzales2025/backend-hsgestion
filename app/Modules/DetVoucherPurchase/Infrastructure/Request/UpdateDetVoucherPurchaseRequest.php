<?php

namespace App\Modules\DetVoucherPurchase\Infrastructure\Request;

class UpdateDetVoucherPurchaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'voucher_id' => 'required',
            'purchase_id' => 'required',
            'amount' => 'required',
        ];
    }
    
}