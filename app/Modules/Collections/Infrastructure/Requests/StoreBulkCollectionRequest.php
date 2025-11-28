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
            "operation_number" => "required|string|max:50",
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

    /* protected function withValidator($validator)
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
    } */

    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.integer' => 'La empresa debe ser un número entero.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.integer' => 'El cliente debe ser un número entero.',
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            
            'payment_method_id.required' => 'El método de pago es obligatorio.',
            'payment_method_id.integer' => 'El método de pago debe ser un número entero.',
            'payment_method_id.exists' => 'El método de pago seleccionado no existe.',
            
            'payment_date.required' => 'La fecha de pago es obligatoria.',
            'payment_date.date' => 'La fecha de pago debe ser una fecha válida.',
            
            'parallel_rate.required' => 'La tasa paralela es obligatoria.',
            'parallel_rate.numeric' => 'La tasa paralela debe ser un número.',
            
            'bank_id.required' => 'El banco es obligatorio.',
            'bank_id.integer' => 'El banco debe ser un número entero.',
            'bank_id.exists' => 'El banco seleccionado no existe.',
            
            'currency_type_id.required' => 'El tipo de moneda es obligatorio.',
            'currency_type_id.integer' => 'El tipo de moneda debe ser un número entero.',
            'currency_type_id.exists' => 'El tipo de moneda seleccionado no existe.',
            
            'operation_date.required' => 'La fecha de operación es obligatoria.',
            'operation_date.date' => 'La fecha de operación debe ser una fecha válida.',
            
            'operation_number.required' => 'El número de operación es obligatorio.',
            'operation_number.string' => 'El número de operación debe ser texto.',
            'operation_number.max' => 'El número de operación no puede exceder 50 caracteres.',
            
            'advance_amount.nullable' => 'El monto de anticipo debe ser un número.',
            'advance_amount.numeric' => 'El monto de anticipo debe ser un número.',
            
            'advance_id.nullable' => 'El anticipo debe ser un número entero.',
            'advance_id.integer' => 'El anticipo debe ser un número entero.',
            'advance_id.exists' => 'El anticipo seleccionado no existe.',
            
            // Cobros
            'collections.required' => 'Debe incluir al menos un cobro.',
            'collections.array' => 'Los cobros deben ser un array.',
            'collections.min' => 'Debe incluir al menos un cobro.',
            
            'collections.*.sale_id.required' => 'La venta es obligatoria en cada cobro.',
            'collections.*.sale_id.integer' => 'La venta debe ser un número entero.',
            'collections.*.sale_id.exists' => 'La venta seleccionada no existe.',
            
            'collections.*.sale_document_type_id.required' => 'El tipo de documento es obligatorio en cada cobro.',
            'collections.*.sale_document_type_id.integer' => 'El tipo de documento debe ser un número entero.',
            'collections.*.sale_document_type_id.exists' => 'El tipo de documento seleccionado no existe.',
            
            'collections.*.serie.required' => 'La serie es obligatoria en cada cobro.',
            'collections.*.serie.string' => 'La serie debe ser texto.',
            
            'collections.*.correlative.required' => 'El correlativo es obligatorio en cada cobro.',
            'collections.*.correlative.string' => 'El correlativo debe ser texto.',
            
            'collections.*.amount.required' => 'El monto es obligatorio en cada cobro.',
            'collections.*.amount.numeric' => 'El monto debe ser un número.',
        ];
    }

}