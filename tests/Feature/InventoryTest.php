<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class InventoryTest extends TestCase {

    public function test_update_stock(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->post(
            '/api/inventory',
            [
                'inventory' => [
                    [
                        'product_id' => 1,
                        'quantity' => 20
                    ]
                ]
            ],
            ['Authorization' => "Bearer $token"]
        )->assertStatus(204);
    }

    public function test_not_update_stock(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->post(
            '/api/inventory',
            [
                'inventory' => [
                    [
                        'product_id' => -9,
                        'quantity' => 20
                    ]
                ]
            ],
            ['Authorization' => "Bearer $token"]
        )->assertStatus(422);
    }

    public function test_get_inventory_list(): void {
        $this->seed();

        $user = User::first();

        $this->assertNotNull($user);

        $token = $user->createToken('user', ['api:user'])->plainTextToken;

        $this->get(
            '/api/inventory?page=1&rows=10',
            ['Authorization' => "Bearer $token"]
        )->assertStatus(200);
    }
}
