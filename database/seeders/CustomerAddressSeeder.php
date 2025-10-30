<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerAddresses = [
            [
                'customer_id' => 1,
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'department_id' => 15,
                'province_id' => 01,
                'district_id' => 01,
                'status' => 1
            ],
            [
                'customer_id' => 2,
                'address' => 'AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA',
                'department_id' => 15,
                'province_id' => 01,
                'district_id' => 01,
                'status' => 1
            ],
            [
                'customer_id' => 3,
                'address' => 'JR. LOS CLAVELES NRO. 265',
                'department_id' => 15,
                'province_id' => 01,
                'district_id' => 01,
                'status' => 1
            ],
            [
                'customer_id' => 4,
                'address' => 'JR. LOS PINOS NRO. SN',
                'department_id' => 15,
                'province_id' => 01,
                'district_id' => 01,
                'status' => 1
            ]
        ];

        DB::table('customer_addresses')->insert($customerAddresses);
    }
}
