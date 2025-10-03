<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            CompanySeeder::class,
            RecordTypeSeeder::class,
            CustomerTypeSeeder::class,
            CurrencyTypeSeeder::class,
            PaymentTypeSeeder::class,
            PaymentMethodSeeder::class,
            DocumentTypeSeeder::class,
            CustomerDocumentTypeSeeder::class,
            RolePermissionSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
        ]);
    }
}
