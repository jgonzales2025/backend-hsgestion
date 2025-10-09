<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'AMD',
                'status' => 1
            ],
            [
                'name' => 'INTEL',
                'status' => 1
            ],
            [
                'name' => 'ASUS',
                'status' => 1
            ],
            [
                'name' => 'SEAGATE',
                'status' => 1
            ],
            [
                'name' => 'KINGSTON',
                'status' => 1
            ]
        ];

        DB::table('brands')->insert($brands);
    }
}
