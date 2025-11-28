<?php

namespace App\Modules\Collections\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreBulkCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'customer_id' => 'required|integer|exists:customers,id',
            "payment_method_id" => "required|integer|exists:payment_methods,id",
            "payment_date" => "required|date",
            "parallel_rate" => "required|numeric",
            "bank_id" => "required|integer|exists:banks,id",
            "currency_type_id" => "required|integer|exists:currency_types,id",
            "operation_date" => "required|date",
            "operation_number" => "required|string",
            "advance_amount" => "nullable|numeric",
            "advance_id" => "nullable|integer|exists:advances,id",
            'collections' => 'required|array|min:1',
            'collections.*.sale_id' => 'required|integer|exists:sales,id',
            'collections.*.sale_document_type_id' => 'required|integer|exists:document_types,id',
            "collections.*.serie" => 'required|string',
            "collections.*.correlative" => 'required|string',
            "collections.*.amount" => 'required|numeric'
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $operationNumber = $this->input('operation_number');
            $bankId = $this->input('bank_id');
            $operationDate = $this->input('operation_date');
            $paymentMethodId = $this->input('payment_method_id');

            // Solo validar si el método de pago es 3 (transferencia bancaria) y todos los campos necesarios están presentes
            if ($paymentMethodId == 3 && $operationNumber && $bankId && $operationDate) {
                try {
                    $date = Carbon::parse($operationDate);
                    $year = $date->year;
                    $month = $date->month;

                    // Verificar si ya existe el mismo operation_number para el mismo banco en el mismo mes
                    $exists = DB::table('collections')
                        ->where('operation_number', $operationNumber)
                        ->where('bank_id', $bankId)
                        ->whereYear('operation_date', $year)
                        ->whereMonth('operation_date', $month)
                        ->exists();

                    if ($exists) {
                        $validator->errors()->add(
                            'operation_number',
                            'El número de operación ya existe para este banco en el mes seleccionado.'
                        );
                    }
                } catch (\Exception $e) {
                    // Si hay error al parsear la fecha, Laravel ya lo manejará con la validación 'date'
                }
            }
        });
    }

}