<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentConcepts = [
            [
                'description' => 'FACTURAS POR PAGAR A PROVEEDORES',
                'status' => 1
            ],
            [
                'description' => 'APLICACIÓN A NOTA DE CRÉDITO',
                'status' => 1
            ],
            [
                'description' => 'RECIBO POR HONORARIO A PROVEEDORES',
                'status' => 1
            ],
            [
                'description' => 'EMITIDAS - SOLES',
                'status' => 1
            ],
            [
                'description' => 'EMITIDAS - DOLARES',
                'status' => 1
            ],
            [
                'description' => 'LETRAS POR PAGAR PROVEEDORES',
                'status' => 1
            ],
            [
                'description' => 'ANTICIPO PROVEEDORES',
                'status' => 1
            ],
            [
                'description' => 'INT. REALC ATRAS OTRAS OBLIG A PLAZOS',
                'status' => 1
            ],
            [
                'description' => 'AJUSTE POR REDONDEO',
                'status' => 1
            ],
            [
                'description' => 'PAGO VARIOS',
                'status' => 1
            ]
        ];

        DB::table('payment_concepts')->insert($paymentConcepts);
    }
}
