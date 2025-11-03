<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ESPECIAL',
                'status' => 1,
                'st_concept' => 1
            ],
            [
                'name' => 'PLACAS MADRE',
                'status' => 1,
                'st_concept' => 0
            ],
            [
                'name' => 'TARJETAS DE VIDEO',
                'status' => 1,
                'st_concept' => 0
            ],
            [
                'name' => 'MONITORES',
                'status' => 1,
                'st_concept' => 0
            ],
            [
                'name' => 'PROCESADOR',
                'status' => 1,
                'st_concept' => 0
            ],
            [
                'name' => 'MEMORIAS RAM',
                'status' => 1,
                'st_concept' => 0
            ],
            [
                'name' => 'LAPTOPS',
                'status' => 1,
                'st_concept' => 0
            ]
        ];

        DB::table('categories')->insert($categories);
    }
}
