<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngressReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emissionReasons = [
            [
                'description' => 'COMPRA',
                'status' => 1
            ],
            [
                'description' => 'IMPORTACION',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION PARA CAMBIO',
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
                'description' => 'TRASLADO ENTRE ESTABLECIMIENTOS',
                'status' => 1
            ],
            [
                'description' => 'OTROS',
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
                'description' => 'DEVOLUCION DE VENTA',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION DE COMPRA',
                'status' => 1
            ],
            [
                'description' => 'INVENTARIO',
                'status' => 1
            ],
            [
                'description' => 'TITULO GRATUITO',
                'status' => 1
            ],
            [
                'description' => 'DEVOLUCION X GARANTIA',
                'status' => 1
            ]
        ];

        DB::table('ingress_reasons')->insert($emissionReasons);
    }
}
