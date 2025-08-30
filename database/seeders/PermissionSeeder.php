<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users permissions
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Roles permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Kartu Keluarga permissions
            'kartukeluarga.view',
            'kartukeluarga.create',
            'kartukeluarga.edit',
            'kartukeluarga.delete',
            
            // Rumah permissions
            'rumah.view',
            'rumah.create',
            'rumah.edit',
            'rumah.delete',
            
            // Data Warga permissions
            'datawarga.view',
            'datawarga.create',
            'datawarga.edit',
            'datawarga.delete',
            
            // Events permissions
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            
            // Banners permissions
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
            
            // Verifikasi Warga permissions
            'verifikasiwarga.view',
            'verifikasiwarga.create',
            'verifikasiwarga.edit',
            'verifikasiwarga.delete',
            
            // Verifikasi IPL permissions - REMOVED (not used)
            // 'verifikasiipl.view',
            // 'verifikasiipl.create',
            // 'verifikasiipl.edit',
            // 'verifikasiipl.delete',
        ];

        foreach ($permissions as $key => $value) {
            Permission::firstOrCreate(['name' => $value, 'guard_name' => 'web']);
        }
    }
}
