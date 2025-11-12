<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PercentageIgvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $percentageIgv = [
            [
                'id' => 1,
                'date' => '2025-07-20',
                'percentage' => 18
            ]
        ];

        DB::table('percentage_igvs')->insert($percentageIgv);
    }
}
