<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $wargaRole = Role::firstOrCreate(['name' => 'warga', 'guard_name' => 'web']);

        // Get all permissions
        $allPermissions = Permission::all();

        // Assign all permissions to superadmin
        $superadminRole->syncPermissions($allPermissions);

        // Assign limited permissions to warga (you can customize this based on your needs)
        $wargaPermissions = [
            'users.view',
            // Add other permissions that warga should have
        ];
        
        $wargaRole->syncPermissions($wargaPermissions);
    }
}