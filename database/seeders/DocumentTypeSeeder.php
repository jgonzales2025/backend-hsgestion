<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            [
                'cod_sunat' => 1,
                'description' => 'FACTURA',
                'abbreviation' => 'FAC',
                'st_sales' => true,
                'st_purchases' => true,
                'st_collections' => true,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 2,
                'description' => 'RECIBO POR HONORARIO',
                'abbreviation' => 'RH',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 3,
                'description' => 'BOLETA DE VENTA',
                'abbreviation' => 'BOL',
                'st_sales' => true,
                'st_purchases' => true,
                'st_collections' => true,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 4,
                'description' => 'LIQUIDACION DE COMPRA',
                'abbreviation' => 'LIQ',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 5,
                'description' => 'BOLETO AEREO',
                'abbreviation' => 'BA',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 6,
                'description' => 'CARTA PORTE AEREO',
                'abbreviation' => 'CPA',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'cod_sunat' => 7,
                'description' => 'NOTA DE CREDITO',
                'abbreviation' => 'N/C',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'NOTA DE DEBITO',
                'cod_sunat' => 8,
                'abbreviation' => 'N/D',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'GUIA DE REMISION',
                'cod_sunat' => 9,
                'abbreviation' => 'G/R',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'RECIBO DE ARRENDAMIENTO',
                'cod_sunat' => 10,
                'abbreviation' => 'RA',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'POLIZA BOLSA VALORES',
                'cod_sunat' => 11,
                'abbreviation' => 'PBV',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'LETRA DE CAMBIO',
                'cod_sunat' => 31,
                'abbreviation' => 'LET',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'TRANSFERENCIA',
                'cod_sunat' => 53,
                'abbreviation' => 'TRA',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'NOTA DE CRED. X DEVO',
                'cod_sunat' => 54,
                'abbreviation' => 'N/C',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'GUIA DE INGRESO INTERNA',
                'cod_sunat' => 60,
                'abbreviation' => 'G/I',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'COTIZACION',
                'cod_sunat' => 0,
                'abbreviation' => 'COT',
                'st_sales' => true,
                'st_purchases' => true,
                'st_collections' => true,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'NOTA DE VENTA',
                'cod_sunat' => 0,
                'abbreviation' => 'N/V',
                'st_sales' => true,
                'st_purchases' => true,
                'st_collections' => true,
                'st_invoices' => false,
                'status' => 1
            ],
            [
                'description' => 'RECIBO DE INGRESO DE CAJA CHICA',
                'cod_sunat' => 78,
                'abbreviation' => 'R/IC',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => true,
                'status' => 1
            ],
            [
                'description' => 'RECIBO DE EGRESO DE CAJA CHICA',
                'cod_sunat' => 77,
                'abbreviation' => 'R/EC',
                'st_sales' => false,
                'st_purchases' => false,
                'st_collections' => false,
                'st_invoices' => true,
                'status' => 1
            ],
        ];

        DB::table('document_types')->insert($documentTypes);
    }
}
