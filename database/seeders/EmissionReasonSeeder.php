<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmissionReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emissionReasons = [
            [
                'description' => 'VENTA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'COMPRA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'IMPORTACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'EXPORTACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'DEVOLUCION X GARANTIA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'TRANSFORMACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'CONSIGNACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'TRASLADO X EMISOR ITINERANTE DE COMPROB.DE PAGO',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'OTROS',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'VENTA DE CONSIGNACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'DEVOLUCION DE CONSIGNACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'CONSUMO INTERNO',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'DEVOLUCION DE COMPRA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'RECOJO DE BIENES',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'VENTA CON ENTREGA A TERCEROS',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'REGULARIZ.TRASLADO ENTRE ESTABLECIMIENTOS',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'PRESTAMO A GARANTIA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'EXHIBICION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'DEMOSTRACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'ENTREGA EN USO',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'TRASLADO PARA PROPIA UTILIZACION',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'CAMBIO DE PRODUCTO',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'PARA REVISION DE GARANTIA',
                'status' => 1,
                'st_transfer' => 0
            ],
            [
                'description' => 'ORDEN INTERNA PARA TRASLADO ENTRE ESTABLECIMIENTOS',
                'status' => 1,
                'st_transfer' => 1
            ]
        ];

        DB::table('emission_reasons')->insert($emissionReasons);
    }
}
