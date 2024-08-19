<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPassword;
use App\Services\AuthService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    public function __construct(string $name)
    {
        $this->authService = new AuthService();

        parent::__construct($name);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(
            route(AuthService::AUTH_ROUTES_NAMES['send-reset-password-link']),
            [
                'email' => $user->email,
            ],
        );

        $response->assertAccepted();

        $response->assertJson(
            fn(AssertableJson $json) => $json
            ->has('message')
            ->whereType('message', 'string')
            ->where('message', 'Password reset link successfully sent'),
        );

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $token = Str::random(60);
        $hashedToken = Hash::make($token);

        DB::table($this->authService->getPasswordResetTokensTableName())->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        Event::fake();

        $response = $this->postJson(
            route(AuthService::AUTH_ROUTES_NAMES['reset-password']),
            [
                'email' => $user->email,
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
                'token' => $token,
            ],
        );

        $response->assertAccepted();

        $response->assertJson(
            fn(AssertableJson $json) => $json
            ->has('message')
            ->where('message', 'Password reset successfully'),
        );

        Event::assertDispatched(PasswordReset::class);
    }
}
