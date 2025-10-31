<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'ruc' => '20537005514',
                'company_name' => 'GRUPO COMPUTEL S.A.C.',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'ubigeo' => '150115',
                'start_date' => '2018-08-20',
                'status' => 1
            ],
            [
                'ruc' => '20608449320',
                'company_name' => 'CORPORACION COMPUTEL E.I.R.L.',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'ubigeo' => '150115',
                'start_date' => '2021-09-10',
                'status' => 1
            ]
        ];

        DB::table('companies')->insert($companies);
    }
}
