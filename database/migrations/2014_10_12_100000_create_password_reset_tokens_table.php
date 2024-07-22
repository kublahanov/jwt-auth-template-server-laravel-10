<?php

use App\Services\AuthService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private AuthService $authService;

    /**
     * Getting table name.
     */
    public function __construct()
    {
        /**
         * TODO: Move to Service Locator?
         */
        $this->authService = new AuthService();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->authService->getPasswordResetTokensTableName(), function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->authService->getPasswordResetTokensTableName());
    }
};
