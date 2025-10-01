<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTypes = [
            [
                'name' => 'CONTADO',
                'status' => 1
            ],
            [
                'name' => 'CREDITO',
                'status' => 1
            ]
        ];

        DB::table('payment_types')->insert($paymentTypes);
    }
}
