<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    /**
     * AAA "Arrange, Act, Assert" - Automated Testing - every function - must have these 3 things
     * 1) Arrange - means prepare scenario - create all need credentials, info, data to work with
     * 2) Act - calling some api, route, url, method - simulate user behavior
     * 3) Assert - tests, assert status etc.
     *
     * * make test for each scenario
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->admin = $this->createUser(true);
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin
        ]);
    }

    public function test_api_returns_products_list(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson('api/products');

        $response->assertJson([$product->toArray()]);
    }


    public function test_api_product_store_successful(): void
    {
        $product = [
            'name' => 'Product 1',
            'price' => 100,
        ];
        $response = $this->postJson('api/products', $product);

        $response->assertStatus(201);
        $response->assertJson($product);
    }

    public function test_api_product_invalid_store_returns_error(): void
    {
        $product = [
            'name' => '',
            'price' => 100,
        ];

        $response = $this->postJson('api/products', $product);
        $response->assertStatus(422);
    }

}
