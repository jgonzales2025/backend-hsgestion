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
                'cia_id' => 1,
                'branch_id' => 1, 
                'serie' => 'T001',
                'correlativo' => "00001",
               
                'emission_reason_id' => 1, 
                'description' => 'Traslado de equipos electrónicos',
                'destination_branch_id' => 2, 
                'destination_address_customer' => 'Av. Los Olivos 456, Lima',
                'transport_id' => 1, 
                'observations' => 'Entregar antes de las 6 PM',
                'num_orden_compra' => 'OC-2025-001',
                'doc_referencia' => 'FA001-000456',
                'num_referencia' => '000456',
                'date_referencia' => Carbon::now()->subDays(2),
                'status' => true,
                'cod_conductor' => 1, 
                'license_plate' => 'ABC-123',
                'total_weight' => 125.75,
                'transfer_type' => 'VENTA',
                'vehicle_type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'document_type_id'=>1,
                'destination_branch_client'=>1,
                'customer_id'=>1,
                'supplier_id' => 1,
                'address_supplier_id' => 1
            ],
            [
                'cia_id' => 1,
                'branch_id' => 1,
                'serie' => 'T002',
                'correlativo' => "00002",
             
                'emission_reason_id' => 1,
                'description' => 'Traslado de mobiliario',
                'destination_branch_id' => 1,
                'destination_address_customer' => 'Jr. San Martín 234, Arequipa',
                'transport_id' => 1, 
                'observations' => 'Revisar embalaje al recibir',
                'num_orden_compra' => 'OC-2025-002',
                'doc_referencia' => 'FA001-000457',
                'num_referencia' => '000457',
                'date_referencia' => Carbon::now()->subDays(1),
                'status' => true,
                'cod_conductor' => 1,
                'license_plate' => 'XYZ-987',
                'total_weight' => 245.40,
                'transfer_type' => 'ALMACÉN',
                'vehicle_type' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'document_type_id'=>1,
                'destination_branch_client'=>1,
                'customer_id'=>1,
                'supplier_id' => 1,
                'address_supplier_id' => 1
            ],
        ];

        DB::table('dispatch_notes')->insert($dispatchNotes);
    }
}
