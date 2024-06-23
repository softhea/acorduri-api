<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_successfully(): void
    {
        $response = $this->postJson(
            '/login',
            [
                'email' => 'superadmin@example.com',
                'password' => 'parola',
            ]
        );

        $response->assertOk();
        $response->assertJsonFragment([
            'message' => 'Logged in!',
        ]);
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_login_fails_when_no_request(): void
    {
        $response = $this->postJson(
            '/login'
        );

        $response->assertUnprocessable();
        $response->assertJson([
            'message' => 'The email field is required. (and 1 more error)',
            'errors' => [
                'email' => [
                    'The email field is required.',
                ],
                'password' => [
                    'The password field is required.',
                ],
            ],
        ]);
    }

    public function test_login_fails_when_invalid_credentials(): void
    {
        $response = $this->postJson(
            '/login',
            [
                'email' => 'aaa@aaa.aaa',
                'password' => 'aaa',
            ]
        );

        $response->assertUnauthorized();
        $response->assertJson([
            'message' => 'Invalid credentials!',
        ]);
    }
}
