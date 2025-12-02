<?php

namespace App\Modules\ScVoucher\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreScVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cia' => 'required|integer',
            'anopr' => 'required|integer',
            'correlativo' => 'required|integer',
            'fecha' => 'required|date',
            'codban' => 'required|integer',
            'codigo' => 'required|integer',
            'nroope' => 'required|string',
            'glosa' => 'nullable|string',
            'orden' => 'nullable|string',
            'tipmon' => 'required|integer',
            'tipcam' => 'required|numeric',
            'total' => 'required|numeric',
            'medpag' => 'required|integer',
            'tipopago' => 'required|integer',
            'status' => 'required|integer',
            'usradi' => 'required|integer',
            'fecadi' => 'required|date',
            'usrmod' => 'required|integer',
            'fecmod' => 'required|date',
        ];
    }
}
