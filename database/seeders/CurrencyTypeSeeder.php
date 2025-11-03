<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencyTypes = [
            [
                'name' => 'SOLES',
                'commercial_symbol' => 'S/ ',
                'sunat_symbol' => 'PEN',
                'status' => 1
            ],
            [
                'name' => 'DOLARES',
                'commercial_symbol' => '$',
                'sunat_symbol' => 'USD',
                'status' => 1
            ]
        ];

        DB::table('currency_types')->insert($currencyTypes);
    }
}
