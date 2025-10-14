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
                'category_id' => 1,
                'name' => 'INALAMBRICO',
                'status' => 1
            ],
            [
                'category_id' => 1,
                'name' => 'CABLE',
                'status' => 1
            ]
        ];

        DB::table('sub_categories')->insert($subCategories);
    }
}
