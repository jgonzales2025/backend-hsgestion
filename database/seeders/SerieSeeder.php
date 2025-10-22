<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $series = [
            [
                'company_id' => 1,
                'serie_number' => '0001',
                'branch_id' => 1,
                'elec_document_type_id' => 0,
                'dir_document_type_id' => 0,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => '0002',
                'branch_id' => 2,
                'elec_document_type_id' => 0,
                'dir_document_type_id' => 0,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'B001',
                'branch_id' => 1,
                'elec_document_type_id' => 3,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'B002',
                'branch_id' => 2,
                'elec_document_type_id' => 3,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'BNC1',
                'branch_id' => 1,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'BNC2',
                'branch_id' => 2,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'BND1',
                'branch_id' => 1,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'BND2',
                'branch_id' => 2,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'F001',
                'branch_id' => 1,
                'elec_document_type_id' => 1,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'F002',
                'branch_id' => 2,
                'elec_document_type_id' => 1,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'FNC1',
                'branch_id' => 1,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'FNC2',
                'branch_id' => 2,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'FND1',
                'branch_id' => 1,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'FND2',
                'branch_id' => 2,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'T001',
                'branch_id' => 1,
                'elec_document_type_id' => 9,
                'dir_document_type_id' => 9,
                'status' => 1
            ],
            [
                'company_id' => 1,
                'serie_number' => 'T002',
                'branch_id' => 2,
                'elec_document_type_id' => 9,
                'dir_document_type_id' => 9,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => '0001',
                'branch_id' => 1,
                'elec_document_type_id' => 0,
                'dir_document_type_id' => 0,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => '0002',
                'branch_id' => 2,
                'elec_document_type_id' => 0,
                'dir_document_type_id' => 0,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'B001',
                'branch_id' => 1,
                'elec_document_type_id' => 3,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'B002',
                'branch_id' => 2,
                'elec_document_type_id' => 3,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'BNC1',
                'branch_id' => 1,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'BNC2',
                'branch_id' => 2,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'BND1',
                'branch_id' => 1,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'BND2',
                'branch_id' => 2,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 3,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'F001',
                'branch_id' => 1,
                'elec_document_type_id' => 1,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'F002',
                'branch_id' => 2,
                'elec_document_type_id' => 1,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'FNC1',
                'branch_id' => 1,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'FNC2',
                'branch_id' => 2,
                'elec_document_type_id' => 7,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'FND1',
                'branch_id' => 1,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'FND2',
                'branch_id' => 2,
                'elec_document_type_id' => 8,
                'dir_document_type_id' => 1,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'T001',
                'branch_id' => 1,
                'elec_document_type_id' => 9,
                'dir_document_type_id' => 9,
                'status' => 1
            ],
            [
                'company_id' => 2,
                'serie_number' => 'T002',
                'branch_id' => 2,
                'elec_document_type_id' => 9,
                'dir_document_type_id' => 9,
                'status' => 1
            ]
        ];

        DB::table('series')->insert($series);
    }
}
