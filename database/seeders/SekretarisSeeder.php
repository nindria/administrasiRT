<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SekretarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Sekre User',
            'email' => 'sekretaris@gmail.com',
            'role' => UserRole::Sekretaris,
            'password' => Hash::make('password'),
        ];

        User::create($data);
    }
}
