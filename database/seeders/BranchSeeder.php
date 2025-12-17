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
                'address' => 'AV. INCA GARCILASO DE LA VEGA NRO. 1348(INT 1049-1053 PISO 1 REF. TDA 1A 164-141) LIMA - LIMA -LIMA',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2025-11-01',
                'serie' => '0001',
                'status' => 1
            ],
            [
                'cia_id' => 1,
                'name' => 'GARANTIAS',
                'address' => 'AV. INCA GARCILASO DE LA VEGA NRO. 1348(INT 1049-1053 PISO 1 REF. TDA 1A 164-141) LIMA - LIMA -LIMA',
                'email' => 'ventas@grupocomputel.com',
                'start_date' => '2025-11-01',
                'serie' => '0002',
                'status' => 1
            ]
        ];

        $branchephones = [
            [
                'branch_id' => 1,
                'phone' => 963852741
            ],
            [
                'branch_id' => 1,
                'phone' => 968574120
            ],
              [
                'branch_id' => 2,
                'phone' => 963852741
            ],
            [
                'branch_id' => 2,
                'phone' => 968574120
            ]
        ];

        DB::table('branches')->insert($branches);
        DB::table('branch_phones')->insert($branchephones);
    }
}
