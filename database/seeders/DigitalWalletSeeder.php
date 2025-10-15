<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $digitalWallets = [
            [
                'name' => 'YAPE',
                'phone' => '963852741',
                'company_id' => 1,
                'user_id' => 1,
                'status' => 1
            ],
            [
                'name' => 'PLIN',
                'phone' => '968574120',
                'company_id' => 1,
                'user_id' => 1,
                'status' => 1
            ],
            [
                'name' => 'YAPE',
                'phone' => '963852741',
                'company_id' => 2,
                'user_id' => 1,
                'status' => 1
            ]
        ];

        DB::table('digital_wallets')->insert($digitalWallets);
    }
}
