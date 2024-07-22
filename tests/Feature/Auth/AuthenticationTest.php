<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Concerns\AssertsStatusCodes;
use Tests\TestCase;

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

        $response = $this->post(route(AuthService::AUTH_ROUTES_NAMES['login']), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertOk();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post(route(AuthService::AUTH_ROUTES_NAMES['login']), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        // $this->assertUnauthorized();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        Auth::login($user);

        $response = $this->actingAs($user)->post(route(AuthService::AUTH_ROUTES_NAMES['logout']));

        $this->assertGuest();
        $response->assertAccepted();
    }
}
