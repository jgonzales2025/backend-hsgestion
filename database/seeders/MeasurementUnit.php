<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MeasurementUnit extends Seeder{
     public function run(): void
    {
        DB::table('measurement_units')->insert([
            [
                'name' => 'Unidad',
                'abbreviation' => 'UND',
                'status' => 1, // Activo
            ],
            [
                'name' => 'Kilogramo',
                'abbreviation' => 'KG',
                'status' => 1, // Activo
            ],
            [
                'name' => 'Litro',
                'abbreviation' => 'LT',
                'status' => 1, // Activo
            ],
        ]);
    }
}