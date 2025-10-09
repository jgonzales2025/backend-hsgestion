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
                'ruc' => '20498189637',
                'company_name' => 'AREQUIPA EXPRESO MARVISUR EIRL',
                'address' => 'CAL.GARCI CARBAJAL NRO. 511 URB. IV CENTENARIO AREQUIPA - AREQUIPA - AREQUIPA',
                'nro_reg_mtc' => 204063,
                'status' => 1
            ],
        ];

        DB::table('transport_companies')->insert($transportCompanies);
    }
}
