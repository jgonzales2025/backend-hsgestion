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
                'st_sales' => 0
            ],
            [
                'record_type_id' => 1,
                'customer_document_type_id' => 2,
                'document_number' => '20608449320',
                'company_name' => 'CORPORACION COMPUTEL E.I.R.L.',
                'customer_type_id' => 1,
                'st_sales' => 0
            ],
            [
                'record_type_id' => 1,
                'customer_document_type_id' => 2,
                'document_number' => '20100073723',
                'company_name' => 'CORPORACION PERUANA DE PRODUCTOS QUIMICOS S.A.',
                'customer_type_id' => 1,
                'st_sales' => 1
            ],
            [
                'record_type_id' => 1,
                'customer_document_type_id' => 2,
                'document_number' => '20493918541',
                'company_name' => 'INVERSIONES MUÑOZ S.A.C.',
                'customer_type_id' => 1,
                'st_sales' => 1
            ]
        ];

        DB::table('customers')->insert($customers);
    }
}
