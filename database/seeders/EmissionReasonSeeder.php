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
                'status' => 1
            ],
            [
                'description' => 'COMPRA',
                'status' => 1
            ],
            [
                'description' => 'IMPORTACION',
                'status' => 1
            ],
            [
                'description' => 'EXPORTACION',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION X GARANTIA',
                'status' => 1
            ],
            [
                'description' => 'TRANSFORMACION',
                'status' => 1
            ],
            [
                'description' => 'CONSIGNACION',
                'status' => 1
            ],
            [
                'description' => 'TRASLADO X EMISOR ITINERANTE DE COMPROB.DE PAGO',
                'status' => 1
            ],
            [
                'description' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA',
                'status' => 1
            ],
            [
                'description' => 'OTROS',
                'status' => 1
            ],
            [
                'description' => 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR',
                'status' => 1
            ],
            [
                'description' => 'VENTA DE CONSIGNACION',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION DE CONSIGNACION',
                'status' => 1
            ],
            [
                'description' => 'CONSUMO INTERNO',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION DE COMPRA',
                'status' => 1
            ],
            [
                'description' => 'RECOJO DE BIENES',
                'status' => 1
            ],
            [
                'description' => 'VENTA CON ENTREGA A TERCEROS',
                'status' => 1
            ],
            [
                'description' => 'REGULARIZ.TRASLADO ENTRE ESTABLECIMIENTOS',
                'status' => 1
            ],
            [
                'description' => 'PRESTAMO A GARANTIA',
                'status' => 1
            ],
            [
                'description' => 'EXHIBICION',
                'status' => 1
            ],
            [
                'description' => 'DEMOSTRACION',
                'status' => 1
            ],
            [
                'description' => 'ENTREGA EN USO',
                'status' => 1
            ],
            [
                'description' => 'TRASLADO PARA PROPIA UTILIZACION',
                'status' => 1
            ],
            [
                'description' => 'CAMBIO DE PRODUCTO',
                'status' => 1
            ],
            [
                'description' => 'PARA REVISION DE GARANTIA',
                'status' => 1
            ]
        ];

        DB::table('emission_reasons')->insert($emissionReasons);
    }
}
