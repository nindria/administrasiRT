<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BendaharaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Bendah User',
            'email' => 'bendahara@gmail.com',
            'role' => UserRole::Bendahara,
            'password' => Hash::make('password'),
        ];

        User::create($data);
    }
}
