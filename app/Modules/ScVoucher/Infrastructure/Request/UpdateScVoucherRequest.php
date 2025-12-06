<?php

namespace App\Modules\ScVoucher\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation()
    {
        $companyId = request()->get('company_id');
        $userId = auth('api')->user()->id;
        $this->merge([
            'cia' => $companyId,
            'usrmod' => $userId,
        ]);
    }

    public function rules(): array
    {
        return [
            'cia' => 'required|integer',
            'anopr' => 'required|integer',
            'correlativo' => 'nullable|string',  
            'fecha' => 'required|date',
            'codban' => 'required|integer',
            'codigo' => 'required|integer',
            'nroope' => 'required|string',
            'glosa' => 'nullable|string',
            'orden' => 'nullable|string',
            'tipmon' => 'required|integer',
            'tipcam' => 'required|numeric',
            'total' => 'required|numeric',
            'medpag_id' => 'required|integer',
            'tipopago' => 'required|integer',
            'status' => 'required|integer',
            'usradi' => 'required|integer',
            'fecadi' => 'required|date',
            'usrmod' => 'nullable|integer',
            'detail_sc_voucher' => 'nullable|array',
            'detail_sc_voucher.*.codcon' => 'required|integer',
            'detail_sc_voucher.*.glosa' => 'required|string',
            'detail_sc_voucher.*.impsol' => 'required|numeric',
            'detail_sc_voucher.*.impdol' => 'required|numeric',
            'detail_voucher_purchase' => 'nullable|array',
            'detail_voucher_purchase.*.purchase_id' => 'required|integer',
            'detail_voucher_purchase.*.amount' => 'required|numeric',

        ];
    }
}
