<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            BranchSeeder::class,
            RecordTypeSeeder::class,
            CustomerTypeSeeder::class,
            CurrencyTypeSeeder::class,
            PaymentTypeSeeder::class,
            MeasurementUnit::class,
            PaymentMethodSeeder::class,
            DocumentTypeSeeder::class,
            CustomerDocumentTypeSeeder::class,
            MenuSeeder::class,
            RolePermissionSeeder::class,
            //UserSeeder::class,
            MenuUserPermission::class,
            BrandSeeder::class,
            DriverSeeder::class,
            TransportCompanySeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ArticleSeeder::class,
            ReferenceCodeSeeder::class,
            IngressReasonSeeder::class,
            BankSeeder::class,
            EmissionReasonSeeder::class,
            DigitalWalletSeeder::class,
            VisibleArticlesSeeder::class
        ]);
    }
}
