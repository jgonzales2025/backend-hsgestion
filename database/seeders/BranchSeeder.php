<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'cia_id' => 1,
                'name' => 'PRINCIPAL',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2021-11-07',
                'serie' => '0001',
                'status' => 1
            ],
            [
                'cia_id' => 1,
                'name' => 'CARABAYLLO',
                'address' => 'AV. CHIMPU OCLLO S/N. MZ. R LOTE 09 URB. VILLA CORPAC.CARABAYLLO',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2021-11-07',
                'serie' => '0002',
                'status' => 1
            ],
            [
                'cia_id' => 2,
                'name' => 'PRINCIPAL',
                'address' => 'AV. GARCILAZO DE LA VEGA N° 1348 TDA. 1A-178/179 LIMA-LIMA-LIMA',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2021-11-07',
                'serie' => '0001',
                'status' => 1
            ],
            [
                'cia_id' => 2,
                'name' => 'SUCURSAL 1B-123',
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 INT. 1035 TDA 1B-123 LIMA-LIMA-LIMA',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2021-11-07',
                'serie' => '0002',
                'status' => 1
            ],
        ];

        DB::table('branches')->insert($branches);
    }
}
