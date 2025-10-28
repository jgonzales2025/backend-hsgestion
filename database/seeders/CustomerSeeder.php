<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'record_type_id' => 1,
                'customer_document_type_id' => 2,
                'document_number' => '20537005514',
                'company_name' => 'GRUPO COMPUTEL S.A.C.',
                'customer_type_id' => 1,
                'status' => 0
            ],
            [
                'record_type_id' => 1,
                'customer_document_type_id' => 2,
                'document_number' => '20608449320',
                'company_name' => 'CORPORACION COMPUTEL E.I.R.L.',
                'customer_type_id' => 1,
                'status' => 0
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
