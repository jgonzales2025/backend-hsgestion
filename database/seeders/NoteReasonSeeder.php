<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $noteReasons = [
            [
                'cod_sunat' => '01',
                'description' => 'ANULACIÓN DE LA OPERACIÓN',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '01',
                'description' => 'INTERESES POR MORA',
                'document_type_id' => 8,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '02',
                'description' => 'ANULACIÓN POR ERROR EN EL RUC',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '02',
                'description' => 'AUMENTO EN EL VALOR',
                'document_type_id' => 8,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '03',
                'description' => 'CORRECCIÓN POR ERROR EN LA DESCRIPCIÓN',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '03',
                'description' => 'PENALIDADES/OTROS CONCEPTOS',
                'document_type_id' => 8,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '04',
                'description' => 'DESCUENTO GLOBAL',
                'document_type_id' => 7,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '05',
                'description' => 'DESCUENTO POR ITEM',
                'document_type_id' => 7,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '06',
                'description' => 'DEVOLUCIÓN TOTAL',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '07',
                'description' => 'DEVOLUCIÓN POR ITEM',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '08',
                'description' => 'BONIFICACIÓN',
                'document_type_id' => 7,
                'stock' => 1,
                'status' => 1
            ],
            [
                'cod_sunat' => '09',
                'description' => 'DISMINUCIÓN EN EL VALOR',
                'document_type_id' => 7,
                'stock' => 2,
                'status' => 1
            ],
            [
                'cod_sunat' => '10',
                'description' => 'OTROS CONCEPTOS',
                'document_type_id' => 7,
                'stock' => 2,
                'status' => 1
            ]
        ];

        DB::table('note_reasons')->insert($noteReasons);
    }
}
