<?php

namespace Database\Seeders;

use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = EloquentUser::create([
            'username' => 'walter',
            'firstname' => 'Walter',
            'lastname' => 'Jave',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);
        $user->assignRole('Gerente', 'Vendedor', 'Contador');

        $user->assignments()->create([
            'company_id' => 1,
            'branch_id' => 1,
            'status' => 1
        ]);

        $user2 = EloquentUser::create([
            'username' => 'erick',
            'firstname' => 'Erick',
            'lastname' => 'Carrillo',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);
        $user2->assignRole('Contador','Cajero');

        $user2->assignments()->create([
            'company_id' => 1,
            'branch_id' => 2,
            'status' => 1
        ]);

        $user3 = EloquentUser::create([
            'username' => 'antonio',
            'firstname' => 'Antonio',
            'lastname' => 'Aranibar',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);
        $user3->assignRole('Cajero');

        $user3->assignments()->create([
            'company_id' => 2,
            'branch_id' => 1,
            'status' => 1
        ]);

        $user4 = EloquentUser::create([
            'username' => 'vendedor1',
            'firstname' => 'vendedor1',
            'lastname' => 'vendedor1',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);

        $user4->assignRole('Vendedor');
        $user4->assignments()->create([
            'company_id' => 1,
            'branch_id' => 1,
            'status' => 1
        ]);

        $user5 = EloquentUser::create([
            'username' => 'vendedor2',
            'firstname' => 'vendedor2',
            'lastname' => 'vendedor2',
            'password' => Hash::make('123456789'),
        ]);

        $user5->assignRole('Vendedor');
        $user5->assignments()->create([
            'company_id' => 2,
            'branch_id' => 1,
            'status' => 1
        ]);

        $user6 = EloquentUser::create([
            'username' => 'almacen1',
            'firstname' => 'almacen1',
            'lastname' => 'almacen1',
            'password' => Hash::make('123456789'),
        ]);
        $user6->assignRole('Almacenero');
        $user6->assignments()->create([
            'company_id' => 1,
            'branch_id' => 1,
            'status' => 1
        ]);

        $user7 = EloquentUser::create([
            'username' => 'almacen2',
            'firstname' => 'almacen2',
            'lastname' => 'almacen2',
            'password' => Hash::make('123456789'),
        ]);
        $user7->assignRole('Almacenero');
        $user7->assignments()->create([
            'company_id' => 2,
            'branch_id' => 1,
            'status' => 1
        ]);
    }
}
