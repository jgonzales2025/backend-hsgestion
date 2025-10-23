<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DispatchNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dispatchNotes = [
            [
                'cia_id' => 1, // id de la compañía (Company)
                'branch_id' => 1, // id de la sucursal origen
                'serie' => 'T001',
                'correlativo' => 1001,
                'date' => Carbon::now(),
                'emission_reason_id' => 1, // id del motivo de emisión
                'description' => 'Traslado de equipos electrónicos',
                'destination_branch_id' => 2, // id de sucursal destino
                'destination_address_customer' => 'Av. Los Olivos 456, Lima',
                'transport_id' => 1, // 🔹 nombre corregido según la migración
                'observations' => 'Entregar antes de las 6 PM',
                'num_orden_compra' => 'OC-2025-001',
                'doc_referencia' => 'FA001-000456',
                'num_referencia' => '000456',
                'serie_referencia' => 'FA001',
                'date_referencia' => Carbon::now()->subDays(2),
                'status' => true,
                'cod_conductor' => 1, // 🔹 nombre corregido según la migración
                'license_plate' => 'ABC-123',
                'total_weight' => 125.75,
                'transfer_type' => 'VENTA',
                'vehicle_type' => 'CAMIÓN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cia_id' => 1,
                'branch_id' => 1,
                'serie' => 'T002',
                'correlativo' => 1002,
                'date' => Carbon::now(),
                'emission_reason_id' => 1,
                'description' => 'Traslado de mobiliario',
                'destination_branch_id' => 1,
                'destination_address_customer' => 'Jr. San Martín 234, Arequipa',
                'transport_id' => 1, // 🔹 corregido
                'observations' => 'Revisar embalaje al recibir',
                'num_orden_compra' => 'OC-2025-002',
                'doc_referencia' => 'FA001-000457',
                'num_referencia' => '000457',
                'serie_referencia' => 'FA001',
                'date_referencia' => Carbon::now()->subDays(1),
                'status' => true,
                'cod_conductor' => 1, // 🔹 corregido
                'license_plate' => 'XYZ-987',
                'total_weight' => 245.40,
                'transfer_type' => 'ALMACÉN',
                'vehicle_type' => 'CAMIONETA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('dispatch_notes')->insert($dispatchNotes);
    }
}
