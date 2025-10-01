<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerDocumentTypes = [
            [
                'cod_sunat' => 0,
                'description' => 'NO DOMICILIADO',
                'abbreviation' => 'NDC',
                'st_driver' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 6,
                'description' => 'REGISTRO UNICO DE CONTRIBUYENTE',
                'abbreviation' => 'RUC',
                'st_driver' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 1,
                'description' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
                'abbreviation' => 'DNI',
                'st_driver' => true,
                'status' => 1
            ],
            [
                'cod_sunat' => 4,
                'description' => 'CARNET DE EXTRANJERIA',
                'abbreviation' => 'EXT',
                'st_driver' => true,
                'status' => 1
            ],
            [
                'cod_sunat' => 7,
                'description' => 'PASAPORTE',
                'abbreviation' => 'PAS',
                'st_driver' => true,
                'status' => 1
            ]
        ];

        DB::table('customer_document_types')->insert($customerDocumentTypes);
    }
}
