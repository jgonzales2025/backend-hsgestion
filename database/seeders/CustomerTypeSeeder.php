<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerTypes = [
            [
                'description' => 'PUBLICO',
                'status' => 1
            ],
            [
                'description' => 'MAYORISTA',
                'status' => 1
            ],
            [
                'description' => 'AUTORIZADO',
                'status' => 1
            ]
        ];

        DB::table('customer_types')->insert($customerTypes);
    }
}
