<?php

namespace Tests\Feature\Api\Authorization;

use Facades\Tests\Setup\UserFactory;
use Tests\Feature\ApiTestCase;
use Vanguard\Http\Resources\PermissionResource;
use Vanguard\Permission;
use Vanguard\User;

class PermissionsControllerTest extends ApiTestCase
{
    /** @test */
    public function unauthenticated()
    {
        $this->getJson('/api/permissions')->assertStatus(401);
    }

    /** @test */
    public function get_users_without_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user, self::API_GUARD)
            ->getJson('/api/permissions')
            ->assertForbidden();
    }

    /** @test */
    public function get_permissions()
    {
        Permission::factory()->times(3)->create();

        $response = $this->actingAs($this->getUser(), self::API_GUARD)
            ->getJson('/api/permissions')
            ->assertOk();

        // 7 default permissions + 3 newly created
        $this->assertCount(10, $response->original);
    }

    /** @test */
    public function get_permission()
    {
        $permission = Permission::factory()->create();

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->getJson("/api/permissions/{$permission->id}")
            ->assertOk()
            ->assertJson([
                'data' => (new PermissionResource($permission))->toArray(request()),
            ]);
    }

    /** @test */
    public function create_permission()
    {
        $data = [
            'name' => 'foo',
            'display_name' => 'Foo Permission',
            'description' => 'This is foo permission.',
        ];

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->postJson('/api/permissions', $data)
            ->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('permissions', $data);
    }

    /** @test */
    public function create_permission_with_invalid_name()
    {
        $this->actingAs($this->getUser(), self::API_GUARD)
            ->postJson('/api/permissions')
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $existingPermission = Permission::first();

        $this->postJson('/api/permissions', ['name' => $existingPermission->name])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $this->postJson('/api/permissions', ['name' => 'foo bar'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function partially_update_permission()
    {
        $this->getUser();

        $permission = Permission::factory()->create();

        $data = ['name' => 'foo'];
        $expected = $data + ['id' => $permission->id];

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->patchJson("/api/permissions/{$permission->id}", $data)
            ->assertJsonFragment($expected);

        $this->assertDatabaseHas('permissions', $expected);
    }

    /** @test */
    public function update_permission()
    {
        $permission = Permission::factory()->create();

        $data = [
            'name' => 'foo',
            'display_name' => 'Foo Role',
            'description' => 'This is foo role.',
        ];
        $expected = $data + ['id' => $permission->id];

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->patchJson("/api/permissions/{$permission->id}", $data)
            ->assertJsonFragment($expected);

        $this->assertDatabaseHas('permissions', $expected);
    }

    /** @test */
    public function remove_permission()
    {
        $permission = Permission::factory()->create(['removable' => true]);

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->deleteJson("/api/permissions/{$permission->id}")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    /** @test */
    public function remove_non_removable_permission()
    {
        $permission = Permission::factory()->create(['removable' => false]);

        $this->actingAs($this->getUser(), self::API_GUARD)
            ->deleteJson("/api/permissions/{$permission->id}")
            ->assertStatus(403);
    }

    /**
     * @return mixed
     */
    private function getUser()
    {
        return UserFactory::user()->withPermissions('permissions.manage')->create();
    }
}
