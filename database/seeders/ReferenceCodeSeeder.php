<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceCodeSeeder extends Seeder{
     public function run():void{
           $referenceCodes = [
            [
                'ref_code'   => 'REF-001',
                'article_id' => 1,
                'date_at'    => "2323",
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ref_code'   => 'REF-002',
                'article_id' => 1,
                'date_at'    => "23233",
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ref_code'   => 'REF-003',
                'article_id' => 1,
                'date_at'    =>"343434",
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reference_codes')->insert($referenceCodes);
        
     }
}