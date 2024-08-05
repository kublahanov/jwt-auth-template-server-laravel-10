<?php

namespace Tests\Feature\Auth;

use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $email = 'test@example.com';
        $password = 'password';

        $registerResponse = $this->postJson(route(AuthService::AUTH_ROUTES_NAMES['register']), [
            'name' => 'Test User',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $registerResponse->assertCreated();

        $registerResponse->assertJson(fn(AssertableJson $json) => $json
            ->has('message')
            ->whereType('message', 'string')
            ->has('user')
            ->whereType('user', 'array')
        );

        $loginResponse = $this->postJson(route(AuthService::AUTH_ROUTES_NAMES['login']), [
            'email' => $email,
            'password' => $password,
        ]);

        $loginResponse->assertOk();

        $this->assertAuthenticated();
    }
}
