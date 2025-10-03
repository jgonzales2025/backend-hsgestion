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
        $user->assignRole('Gerente');

        $user2 = EloquentUser::create([
            'username' => 'erick',
            'firstname' => 'Erick',
            'lastname' => 'Carrillo',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);
        $user2->assignRole('Contador');

        $user3 = EloquentUser::create([
            'username' => 'antonio',
            'firstname' => 'Antonio',
            'lastname' => 'Aranibar',
            'password' => Hash::make('123456789'),
            'status' => 1
        ]);
        $user3->assignRole('Cajero');
    }
}
