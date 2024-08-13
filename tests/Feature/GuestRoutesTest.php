<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Throwable;

class GuestRoutesTest extends TestCase
{
    public function test_home_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @throws Throwable
     */
    public function test_api_list_returns_a_successful_response(): void
    {
        $response = $this->getJson(route('api-list'));

        $response->assertStatus(200);

        $json = $response->decodeResponseJson();
        $this->assertIsObject($json);
    }
}
