<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'description' => 'EFECTIVO',
                'status' => 1,
                'st_visible' => 1
            ],
            [
                'description' => 'TARJETA',
                'status' => 1,
                'st_visible' => 1
            ],
            [
                'description' => 'DEPÓSITO BANCARIO',
                'status' => 1,
                'st_visible' => 1
            ],
            [
                'description' => 'BILLETERA DIGITAL',
                'status' => 1,
                'st_visible' => 1
            ],
            [
                'description' => 'NOTA DE CRÉDITO',
                'status' => 1,
                'st_visible' => 0
            ]
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }
}
