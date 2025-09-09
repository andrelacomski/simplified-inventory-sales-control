<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SaleTest extends TestCase {
    public function test_sell_successfully(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->post(
            '/api/sales',
            [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 1
                    ]
                ]
            ],
            ['Authorization' => "Bearer $token"]
        )->assertStatus(200);
    }

    public function test_sell_fail(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->post(
            '/api/sales',
            [
                'products' => [
                    [
                        'product_id' => 1,
                        'quantity' => 2000
                    ]
                ]
            ],
            ['Authorization' => "Bearer $token"]
        )->assertStatus(422);
    }

    public function test_get_sale(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->get(
            '/api/sales/1',
            ['Authorization' => "Bearer $token"]
        )->assertStatus(200);
    }

    public function test_not_get_sale(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->get(
            '/api/sales/-2',
            ['Authorization' => "Bearer $token"]
        )->assertStatus(404);
    }
}
