<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'customer_document_type_id' => 3,
                'doc_number' => '75524314',
                'name' => 'JUAN LEONARDO',
                'pat_surname' => 'ANTON',
                'mat_surname' => 'SANTAMARIA',
                'license' => 'Q75524314',
                'status' => 1,
            ],
            [
                'customer_document_type_id' => 3,
                'doc_number' => '43023680',
                'name' => 'JUAN CARLOS',
                'pat_surname' => 'CHAMPI',
                'mat_surname' => 'CONDORI',
                'license' => 'Q43023680',
                'status' => 1,
            ],
            [
                'customer_document_type_id' => 3,
                'doc_number' => '42101320',
                'name' => 'ALEXANDER NICANOR',
                'pat_surname' => 'QUISPE',
                'mat_surname' => 'NIETO',
                'license' => 'Q42101320',
                'status' => 1,
            ]
        ];
        DB::table('drivers')->insert($drivers);
    }
}
