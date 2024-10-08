<?php

namespace Tests\Feature\Api\Users;

use Facades\Tests\Setup\UserFactory;
use Illuminate\Support\Str;
use Tests\Feature\ApiTestCase;
use Vanguard\Http\Resources\SessionResource;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\User;

class SessionsControllerTest extends ApiTestCase
{
    /** @test */
    public function get_sessions_unauthenticated()
    {
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}/sessions")->assertStatus(401);
    }

    /** @test */
    public function get_user_sessions_without_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user, self::API_GUARD)
            ->getJson("/api/users/{$user->id}/sessions")
            ->assertForbidden();
    }

    /** @test */
    public function get_user_sessions()
    {
        config(['session.driver' => 'database']);

        $user = UserFactory::withPermissions('users.manage')->create();

        $sessions = $this->generateNonExpiredSessions($user);

        $this->actingAs($user, self::API_GUARD)
            ->getJson("/api/users/{$user->id}/sessions")
            ->assertOk()
            ->assertJson([
                'data' => SessionResource::collection($sessions)->resolve(),
            ]);
    }

    private function generateNonExpiredSessions(User $user, $count = 5)
    {
        $sessions = [];
        $faker = $this->app->make(\Faker\Generator::class);
        $lifetime = config('session.lifetime') - 1;

        for ($i = 0; $i < $count; $i++) {
            array_push($sessions, [
                'id' => Str::random(40),
                'user_id' => $user->id,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'payload' => Str::random(),
                'last_activity' => $faker->dateTimeBetween("-{$lifetime} minutes")->getTimestamp(),
            ]);
        }

        \DB::table('sessions')->insert($sessions);

        return app(SessionRepository::class)->getUserSessions($user->id);
    }
}
