<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class  DispatchArticle extends Seeder{
  public function run(): void
{
    $dispatchArticles = [
        [
            'dispatch_id' => 1,
            'article_id' => 1,
            'quantity' => 10.00,
            'weight' => 2.50,
            'saldo' => 5.00,
            'name' => 'Monitor LED 24"',
            'subtotal_weight' => 25.00, 
        ],
        [
            'dispatch_id' => 1,
            'article_id' => 2,
            'quantity' => 5.00,
            'weight' => 1.20,
            'saldo' => 2.00,
            'name' => 'Teclado mecánico RGB',
            'subtotal_weight' => 6.00, 
        ],
        [
            'dispatch_id' => 2,
            'article_id' => 1,
            'quantity' => 8.00,
            'weight' => 3.10,
            'saldo' => 1.00,
            'name' => 'Silla ergonómica',
            'subtotal_weight' => 24.80, 
        ],
    ];

    DB::table('dispatch_article')->insert($dispatchArticles);
}

}