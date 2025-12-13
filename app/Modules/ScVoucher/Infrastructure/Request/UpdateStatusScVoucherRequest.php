<?php

namespace App\Modules\ScVoucher\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusScVoucherRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:1,0',
        ];
    }
}