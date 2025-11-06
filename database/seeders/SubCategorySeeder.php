<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subCategories = [
            [
                'category_id' => 2,
                'name' => 'INALAMBRICO',
                'status' => 1
            ],
            [
                'category_id' => 3,
                'name' => 'CABLE',
                'status' => 1
            ],
            [
                'category_id' => 4,
                'name' => 'PROGRAMADOR',
                'status' => 1
            ],
            [
                'category_id' => 5,
                'name' => 'FRONTEND',
                'status' => 1
            ],
            [
                'category_id' => 6,
                'name' => 'BACKEND',
                'status' => 1
            ]
        ];

        DB::table('sub_categories')->insert($subCategories);
    }
}
