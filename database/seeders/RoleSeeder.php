<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        // Assign limited permissions to warga (only view permissions)
        $wargaPermissions = [
            'users.view',
            'kartukeluarga.view',
            'rumah.view',
            'datawarga.view',
            'events.view',
            'banners.view',
            'verifikasiwarga.view',
        ];

        $wargaRole->syncPermissions($wargaPermissions);
    }
}
