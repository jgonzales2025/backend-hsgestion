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
                'name' => 'IMPRESORAS',
                'status' => 1
            ],
            [
                'name' => 'PLACAS MADRE',
                'status' => 1
            ],
            [
                'name' => 'TARJETAS DE VIDEO',
                'status' => 1
            ],
            [
                'name' => 'MONITORES',
                'status' => 1
            ],
            [
                'name' => 'PROCESADOR',
                'status' => 1
            ],
            [
                'name' => 'MEMORIAS RAM',
                'status' => 1
            ],
            [
                'name' => 'LAPTOPS',
                'status' => 1
            ]
        ];

        DB::table('categories')->insert($categories);
    }
}
