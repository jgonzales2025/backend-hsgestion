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
            'cia' => 'nullable|integer',
            'anopr' => 'required|string',
            'fecha' => 'required|date',
            'codban' => 'required|integer',
            'codigo' => 'required|integer',
            'nroope' => 'required|string',
            'glosa' => 'nullable|string',
            'orden' => 'nullable|string',
            'tipmon' => 'required|integer',
            'tipcam' => 'required|numeric',
            'total' => 'required|numeric|min:1',
            'medpag_id' => 'required|integer',
            'tipopago' => 'required|integer',
            'status' => 'nullable|integer',
            'usradi' => 'required|integer',
            'fecadi' => 'nullable|date',
            'usrmod' => 'nullable|integer',

            'detail_sc_voucher' => 'required|array',
            'detail_sc_voucher.*.codcon' => 'required|integer',
            'detail_sc_voucher.*.glosa' => 'nullable|string',
            'detail_sc_voucher.*.impsol' => 'required|numeric',
            'detail_sc_voucher.*.impdol' => 'required|numeric',
            'detail_sc_voucher.*.tipdoc' => 'required|integer',
            'detail_sc_voucher.*.numdoc' => 'required|string',
            'detail_sc_voucher.*.correlativo' => 'required|string', 
            'detail_sc_voucher.*.id_purchase' => 'nullable|integer|exists:purchase,id',

            'detail_voucher_purchase' => 'nullable|array',
            'detail_voucher_purchase.*.purchase_id' => 'nullable|integer|exists:purchase,id',
            'detail_voucher_purchase.*.amount' => 'nullable|numeric',
        ];
    }
    public function messages(): array
    {
        return [
            'total.required' => 'Debe ingresar un total.',
            'total.numeric' => 'El total debe ser un numero.',
            'total.min' => 'El total debe ser mayor a 0.',

            'detail_sc_voucher.*.id_purchase.exists' => 'la compra que deseas crear no existe',
            'detail_sc_voucher.*.codcon.required' => 'Debe seleccionar una cuenta.',
            'detail_sc_voucher.*.impsol.required' => 'Debe ingresar un importe.',
            'detail_sc_voucher.*.impdol.required' => 'Debe ingresar un importe.',

            'detail_voucher_purchase.*.amount.required' => 'Debe ingresar un importe.',
            'detail_sc_voucher.*.glosa.string' => 'La glosa debe ser una cadena de texto.',
            'detail_sc_voucher.*.impsol.numeric' => 'El importe debe ser un numero.',
            'detail_sc_voucher.*.impdol.numeric' => 'El importe debe ser un numero.',
            'detail_voucher_purchase.*.amount.numeric' => 'El importe debe ser un numero.',
            'detail_voucher_purchase.*.purchase_id.exists' => 'la compra que deseas crear no existe',
        ];
    }
}
