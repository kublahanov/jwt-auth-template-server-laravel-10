<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Concerns\AssertsStatusCodes;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tymon\JWTAuth\JWTGuard;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, AssertsStatusCodes;

    protected AuthService $authService;

    public function __construct(string $name)
    {
        $this->authService = new AuthService();

        parent::__construct($name);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route(AuthService::AUTH_ROUTES_NAMES['login']), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();

        $response->assertJson(fn(AssertableJson $json) => $json
            ->has('access_token')
            ->whereType('access_token', 'string')
            ->where('token_type', 'bearer')
            ->has('expires_in')
            ->whereType('expires_in', 'integer')
        );

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route(AuthService::AUTH_ROUTES_NAMES['login']), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();

        $response->assertJson(fn(AssertableJson $json) => $json
            ->has('error')
            ->where('error', 'InvalidCredentialsException')
            ->has('message')
            ->whereType('message', 'string')
            ->etc()
        );

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        /* @var $auth JWTGuard */
        $auth = auth();

        $token = $auth->login($user);

        $response = $this
            // ->actingAs($user)
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route(AuthService::AUTH_ROUTES_NAMES['logout']))
        ;

        $response->assertAccepted();

        $response->assertJson(fn(AssertableJson $json) => $json
            ->has('message')
            ->whereType('message', 'string')
        );

        $this->assertGuest();
    }
}
