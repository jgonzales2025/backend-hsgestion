<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PettyCashMotiveSeeder extends Seeder
{
    public function run()
    {
        DB::table('petty_cash_motive')->insert([
            [
                'company_id' => 1,
                'description' => 'Compra de materiales de oficina',
                'receipt_type' => 18,
                'user_id' => 1,
                'status' => 1,
            ],
            [
                'company_id' => 1,
                'description' => 'Movilidad local',
                'receipt_type' => 19,
                'user_id' => 1,
                'status' => 1,
            ],
        ]);
    }
}
