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
                'ruc' => '20614604825',
                'company_name' => 'CYBERHOUSE TEC S.A.C.',
                'address' => 'AV. INCA GARCILASO DE LA VEGA NRO. 1348(INT 1049-1053 PISO 1 REF. TDA 1A 164-141) LIMA - LIMA -LIMA',
                'ubigeo' => '150115',
                'start_date' => '2025-11-01',
                'default_currency_type_id' => 1,
                'min_profit' => 5,
                'max_profit' => 15,
                'usuario_sol' => 'RTHEDSTA',
                'clave_sol' => 'siousketr',
                'status' => 1
            ]
        ];

        DB::table('companies')->insert($companies);
    }
}
