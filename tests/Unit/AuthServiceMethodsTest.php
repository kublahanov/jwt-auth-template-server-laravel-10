<?php

namespace Tests\Unit;

use App\Services\AuthService;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class AuthServiceMethodsTest extends TestCase
{
    private AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();

        $this->authService = new AuthService;
    }

    public function test_get_password_reset_tokens_table_name(): void
    {
        $this->assertNotEmpty($this->authService->getPasswordResetTokensTableName());
    }
}
