<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
       $articles =  [
            [
                'cod_fab' => 'ART-001',
                'description' => 'Monitor LED 24 pulgadas',
                'weight' => 3.5,
                'with_deduction' => false,
                'series_enabled' => false,
                'measurement_unit_id' => 1,
                'brand_id' => 1,
                'category_id' => 1,
                'location' => 'A1-01',
                'warranty' => '1 año',
                'tariff_rate' => 18.00,
                'igv_applicable' => true,
                'plastic_bag_applicable' => false,
                'min_stock' => 10,
                'currency_type_id' => 1,
                'purchase_price' => 500.00,
                'public_price' => 575.00,
                'distributor_price' => 550.00,
                'authorized_price' => 540.00,
                'public_price_percent' => 10.0,
                'distributor_price_percent' => 5.0,
                'authorized_price_percent' => 3.0,
                'status' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'venta' => true,
                'sub_category_id'=>1,
                'company_type_id'=>1
            ]
            ];
      DB::table('articles')->insert($articles);

    }
}
