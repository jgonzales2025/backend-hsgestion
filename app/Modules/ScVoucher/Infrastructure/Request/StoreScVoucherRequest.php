<?php

namespace App\Modules\ScVoucher\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreScVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation()
    {
        $companyId = request()->get('company_id');
        $user = auth('api')->user();
        $this->merge([
            'cia' => $companyId,
            'usrmod' => $user->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'cia' => 'nullable|integer',
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
            'status' => 'nullable|integer',
            'usradi' => 'required|integer',
            'fecadi' => 'required|date',
            'usrmod' => 'nullable|integer',
            'detail_sc_voucher' => 'required|array',
            'detail_sc_voucher.*.codcon' => 'required|integer',
            'detail_sc_voucher.*.numdoc' => 'required|string',
            'detail_sc_voucher.*.glosa' => 'required|string',
            'detail_sc_voucher.*.impsol' => 'required|numeric',
            'detail_sc_voucher.*.impdol' => 'required|numeric',


        ];
    }
}
