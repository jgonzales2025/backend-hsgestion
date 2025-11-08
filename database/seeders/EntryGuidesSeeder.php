<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntryGuidesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('entry_guides')->insert([
            'cia_id' => 1,
            'branch_id' => 1,
            'customer_id' => 1,
            'ingress_reason_id' => 1,
            'serie' => 'EG-001',
            'correlative' => '0001',
            'date' => now(),
            'observations' => 'Ingreso de productos de prueba',
            'guide_serie_supplier' => 'G-2025',
            'guide_correlative_supplier' => '00123',
            'invoice_serie_supplier' => 'F-2025',
            'invoice_correlative_supplier' => '00456',
            'reference_serie' => 'OC-2025',
            'reference_correlative' => '000789',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
