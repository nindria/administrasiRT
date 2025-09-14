<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        $permissions = [
            'datawarga.view', 'datawarga.create', 'datawarga.edit', 'datawarga.delete',
            'kartukeluarga.view', 'kartukeluarga.create', 'kartukeluarga.edit', 'kartukeluarga.delete',
            'rumah.view', 'rumah.create', 'rumah.edit', 'rumah.delete',
            'events.view', 'events.create', 'events.edit', 'events.delete',
            'banners.view', 'banners.create', 'banners.edit', 'banners.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $superadminRole = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $wargaRole = Role::create(['name' => 'warga', 'guard_name' => 'web']);

        // Assign permissions
        $superadminRole->givePermissionTo(Permission::all());
        $wargaRole->givePermissionTo([
            'datawarga.view', 'kartukeluarga.view', 'rumah.view', 'events.view', 'banners.view',
        ]);
    }

    public function test_superadmin_can_access_all_resources()
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        // Test datawarga routes
        $response = $this->actingAs($user)->get(route('datawarga.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('datawarga.create'));
        $response->assertStatus(200);

        // Test kartukeluarga routes
        $response = $this->actingAs($user)->get(route('kartukeluarga.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('kartukeluarga.create'));
        $response->assertStatus(200);

        // Test rumah routes
        $response = $this->actingAs($user)->get(route('rumah.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('rumah.create'));
        $response->assertStatus(200);

        // Test events routes
        $response = $this->actingAs($user)->get(route('events.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('events.create'));
        $response->assertStatus(200);

        // Test banners routes
        $response = $this->actingAs($user)->get(route('banners.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('banners.create'));
        $response->assertStatus(200);
    }

    public function test_warga_can_only_view_resources()
    {
        $user = User::factory()->create();
        $user->assignRole('warga');

        // Test datawarga - can view
        $response = $this->actingAs($user)->get(route('datawarga.index'));
        $response->assertStatus(200);

        // Test datawarga - cannot create
        $response = $this->actingAs($user)->get(route('datawarga.create'));
        $response->assertStatus(403);

        // Test kartukeluarga - can view
        $response = $this->actingAs($user)->get(route('kartukeluarga.index'));
        $response->assertStatus(200);

        // Test kartukeluarga - cannot create
        $response = $this->actingAs($user)->get(route('kartukeluarga.create'));
        $response->assertStatus(403);

        // Test rumah - can view
        $response = $this->actingAs($user)->get(route('rumah.index'));
        $response->assertStatus(200);

        // Test rumah - cannot create
        $response = $this->actingAs($user)->get(route('rumah.create'));
        $response->assertStatus(403);

        // Test events - can view
        $response = $this->actingAs($user)->get(route('events.index'));
        $response->assertStatus(200);

        // Test events - cannot create
        $response = $this->actingAs($user)->get(route('events.create'));
        $response->assertStatus(403);

        // Test banners - can view
        $response = $this->actingAs($user)->get(route('banners.index'));
        $response->assertStatus(200);

        // Test banners - cannot create
        $response = $this->actingAs($user)->get(route('banners.create'));
        $response->assertStatus(403);
    }

    public function test_warga_cannot_update_or_delete_resources()
    {
        $user = User::factory()->create();
        $user->assignRole('warga');

        // Test that warga cannot access edit routes
        $response = $this->actingAs($user)->put(route('datawarga.update', 1), []);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('datawarga.destroy', 1));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('kartukeluarga.update', 1), []);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('kartukeluarga.destroy', 1));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('events.update', 1), []);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('events.destroy', 1));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('banners.update', 1), []);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('banners.destroy', 1));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        // Test that unauthenticated users get 403 or are redirected to login
        $response = $this->get(route('datawarga.index'));
        $this->assertTrue($response->status() === 403 || $response->isRedirect());

        $response = $this->get(route('kartukeluarga.index'));
        $this->assertTrue($response->status() === 403 || $response->isRedirect());

        $response = $this->get(route('rumah.index'));
        $this->assertTrue($response->status() === 403 || $response->isRedirect());

        $response = $this->get(route('events.index'));
        $this->assertTrue($response->status() === 403 || $response->isRedirect());

        $response = $this->get(route('banners.index'));
        $this->assertTrue($response->status() === 403 || $response->isRedirect());
    }

    public function test_user_without_role_cannot_access_resources()
    {
        $user = User::factory()->create();
        // User has no role assigned

        $response = $this->actingAs($user)->get(route('datawarga.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('kartukeluarga.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('rumah.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('events.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('banners.index'));
        $response->assertStatus(403);
    }
}
