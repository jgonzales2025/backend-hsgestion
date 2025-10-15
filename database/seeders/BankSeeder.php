<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'name' => 'BANCO DE LA NACION',
                'account_number' => '459632147896',
                'currency_type_id' => 1,
                'user_id' => 1,
                'company_id' => 1,
                'status' => 1
            ],
            [
                'name' => 'BANCO DE CREDITO',
                'account_number' => '649685748520',
                'currency_type_id' => 1,
                'user_id' => 1,
                'company_id' => 1,
                'status' => 0
            ],
            [
                'name' => 'BANCO DE LA NACION',
                'account_number' => '459632147896',
                'currency_type_id' => 1,
                'user_id' => 1,
                'company_id' => 2,
                'status' => 1
            ],
            [
                'name' => 'BANCO DE CREDITO',
                'account_number' => '649685748520',
                'currency_type_id' => 1,
                'user_id' => 1,
                'company_id' => 2,
                'status' => 0
            ],
        ];

        DB::table('banks')->insert($banks);
    }
}
