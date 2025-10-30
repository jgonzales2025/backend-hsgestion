<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportCompanies = [
            [
                'ruc' => '20537005514',
                'company_name' => 'GRUPO COMPUTEL S.A.C.',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'nro_reg_mtc' => 151515,
                'status' => 1,
                'st_private' => 1
            ],
            [
                'ruc' => '20608449320',
                'company_name' => 'CORPORACION COMPUTEL E.I.R.L.',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'nro_reg_mtc' => 151516,
                'status' => 1,
                'st_private' => 1
            ],
            [
                'ruc' => '20498189637',
                'company_name' => 'AREQUIPA EXPRESO MARVISUR EIRL',
                'address' => 'CAL.GARCI CARBAJAL NRO. 511 URB. IV CENTENARIO AREQUIPA - AREQUIPA - AREQUIPA',
                'nro_reg_mtc' => 204063,
                'status' => 1,
                'st_private' => 0,
            ],
            [
                'ruc' => '20600968574',
                'company_name' => 'SERVICIOS Y TRANSPORTE HERMANOS UNIDOS E.I.R.L.',
                'address' => 'CAL.GARCI CARBAJAL NRO. 511 URB. IV CENTENARIO AREQUIPA - AREQUIPA - AREQUIPA',
                'nro_reg_mtc' => 179652,
                'status' => 1,
                'st_private' => 0,
            ]
        ];

        DB::table('transport_companies')->insert($transportCompanies);
    }
}
