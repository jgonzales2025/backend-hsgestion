<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecordTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recordTypes = [
            [
                'name' => 'PROVEEDORES',
                'abbreviation' => 'PRO',
                'status' => 1
            ],
            [
                'name' => 'CLIENTES',
                'abbreviation' => 'CLI',
                'status' => 1
            ],
            [
                'name' => 'PROVEED/CLIENTE',
                'abbreviation' => 'AMB',
                'status' => 1
            ]
        ];

        DB::table('record_types')->insert($recordTypes);
    }
}
