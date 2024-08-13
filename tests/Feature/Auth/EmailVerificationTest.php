<?php

namespace Tests\Feature\Auth;

use App\Helpers\TestHelper;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use ReflectionException;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws ReflectionException
     */
    public function test_email_can_be_verified(): void
    {
        $user = User::factory()
            ->unverified()
            ->create()
        ;

        $this->assertFalse($user->hasVerifiedEmail());

        Event::fake();

        $verifyEmail = new VerifyEmail;
        $verificationUrl = TestHelper::invokeProtectedMethod($verifyEmail, 'verificationUrl', [$user]);
        $this->getJson($verificationUrl);

        Event::assertDispatched(Verified::class);

        $user->refresh();
        $this->assertTrue($user->hasVerifiedEmail());
    }
}
