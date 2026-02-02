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
            'usradi' => $user->id,
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
            'nroope' => 'nullable|string',
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
            'path_image' => 'nullable|image|max:2048',

            'detail_sc_voucher' => 'required|array',
            'detail_sc_voucher.*.codcon' => 'required|integer',
            'detail_sc_voucher.*.glosa' => 'nullable|string',
            'detail_sc_voucher.*.impsol' => 'required|numeric',
            'detail_sc_voucher.*.impdol' => 'required|numeric',
            'detail_sc_voucher.*.tipdoc' => 'required|integer',
            'detail_sc_voucher.*.correlativo' => 'required|string',
            'detail_sc_voucher.*.id_purchase' => 'nullable|integer|exists:purchase,id',
            'detail_sc_voucher.*.serie' => 'nullable|string',

            'detail_voucher_purchase' => 'nullable|array',
            'detail_voucher_purchase.*.purchase_id' => 'nullable|integer|exists:purchase,id',
            'detail_voucher_purchase.*.amount' => 'nullable|numeric',

        ];
    }

    public function messages(): array
    {
        return [
            'cia.required' => 'Debe seleccionar una empresa.',
            'anopr.required' => 'Debe ingresar el año.',
            'anopr.string' => 'El año debe ser una cadena de texto.',
            'fecha.required' => 'Debe ingresar una fecha.',
            'fecha.date' => 'La fecha debe ser una fecha.',
            'codban.required' => 'Debe seleccionar una banco.',
            'codban.exists' => 'Selecciona un banco',
            'codigo.required' => 'Debe ingresar el codigo.',
            'codigo.integer' => 'El codigo debe ser un numero.',
            'nroope.string' => 'El numero debe ser una cadena de texto.',
            'glosa.string' => 'La glosa debe ser una cadena de texto.',
            'orden.string' => 'El orden debe ser una cadena de texto.',
            'tipmon.required' => 'Debe seleccionar una moneda.',
            'tipmon.exists' => 'Selecciona una moneda',
            'tipcam.required' => 'Debe ingresar el tipo de cambio.',
            'tipcam.numeric' => 'El tipo de cambio debe ser un numero.',
            'total.required' => 'Debe ingresar el total.',
            'total.min' => 'El total debe ser mayor a 0.',
            'total.numeric' => 'El total debe ser un numero.',
            'medpag_id.required' => 'Debe seleccionar un medio de pago.',
            'medpag_id.exists' => 'Selecciona un medio de pago',
            'tipopago.required' => 'Debe seleccionar un tipo de pago.',
            'tipopago.exists' => 'Selecciona un tipo de pago',
            'status.required' => 'Debe seleccionar un estado.',
            'status.exists' => 'Selecciona un estado',
            'fecadi.date' => 'La fecha debe ser una fecha.',

            'detail_sc_voucher.required' => 'Debe seleccionar al menos un detalle.',
            'detail_sc_voucher.array' => 'Los detalles deben ser un array.',
            'detail_sc_voucher.min' => 'Debe seleccionar al menos un detalle.',
            'detail_sc_voucher.*.codcon.required' => 'Debe seleccionar una cuenta.',
            'detail_sc_voucher.*.codcon.exists' => 'Selecciona una cuenta',
            'detail_sc_voucher.*.impsol.required' => 'Debe ingresar el importe sol.',
            'detail_sc_voucher.*.impsol.numeric' => 'El importe sol debe ser un numero.',
            'detail_sc_voucher.*.impdol.required' => 'Debe ingresar el importe dol.',
            'detail_sc_voucher.*.impdol.numeric' => 'El importe dol debe ser un numero.',
            'detail_sc_voucher.*.serie.max' => 'La serie no puede tener mas de 4 caracteres.',
            'detail_sc_voucher.*.id_purchase.exists' => 'la compra que deseas crear no existe',

            'detail_voucher_purchase.array' => 'Los detalles de la compra deben ser un array.',
            'detail_voucher_purchase.*.purchase_id.required' => 'Debe seleccionar una compra.',
            'detail_voucher_purchase.*.purchase_id.exists' => 'Selecciona una compra',
            'detail_voucher_purchase.*.amount.required' => 'Debe ingresar el monto.',
            'detail_voucher_purchase.*.amount.numeric' => 'El monto debe ser un numero.',
            'detail_voucher_purchase.*.purchase_id.exists' => 'la compra que deseas crear no existe',
        ];
    }
}
