<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase {

    public function test_user_can_authenticate(): void {
        $this->seed();

        $user = User::where('email', 'admin@cplug.com.br')->first();

        $this->assertNotNull($user);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($response['token']);
    }

    public function test_user_can_not_authenticate(): void {
        $this->seed();

        $user = User::where('email', 'admin@cplug.com.br')->first();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '1234'
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_authenticate_and_logout(): void {
        $this->seed();

        $user = User::where('email', 'admin@cplug.com.br')->first();

        $this->assertNotNull($user);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $token = $response['token'];

        $this->post('/api/logout', [], [
            'Authorization' => "Bearer $token"
        ])->assertStatus(200);
    }
}
