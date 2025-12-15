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
            'usradi' => $userId,
        ]);
    }

    public function rules(): array
    {
        return [
            'cia' => 'required|integer',
            'anopr' => 'required|string',
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
            'fecadi' => 'nullable|date',
            'usrmod' => 'nullable|integer',
            'detail_sc_voucher' => 'nullable|array',
            'detail_sc_voucher.*.codcon' => 'required|integer',
            'detail_sc_voucher.*.glosa' => 'nullable|string',
            'detail_sc_voucher.*.impsol' => 'required|numeric',
            'detail_sc_voucher.*.impdol' => 'required|numeric',
            'detail_voucher_purchase' => 'nullable|array',
            'detail_voucher_purchase.*.purchase_id' => 'required|integer',
            'detail_voucher_purchase.*.amount' => 'required|numeric',
            'detail_sc_voucher.*.tipdoc' => 'required|integer',
            'detail_sc_voucher.*.numdoc' => 'required|string',
            'detail_sc_voucher.*.correlativo' => 'required|string',
            'detail_sc_voucher.*.serie' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'detail_sc_voucher.*.codcon.required' => 'Debe seleccionar una cuenta.',
            'detail_sc_voucher.*.impsol.required' => 'Debe ingresar un importe.',
            'detail_sc_voucher.*.impdol.required' => 'Debe ingresar un importe.',
            'detail_voucher_purchase.*.purchase_id.required' => 'Debe seleccionar un documento.',
            'detail_voucher_purchase.*.amount.required' => 'Debe ingresar un importe.',
            'detail_sc_voucher.*.glosa.string' => 'La glosa debe ser una cadena de texto.',
            'detail_sc_voucher.*.impsol.numeric' => 'El importe debe ser un numero.',
            'detail_sc_voucher.*.impdol.numeric' => 'El importe debe ser un numero.',
            'detail_voucher_purchase.*.amount.numeric' => 'El importe debe ser un numero.',
        ];
    }
}
