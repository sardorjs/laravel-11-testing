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

    /**
     * To run specific test(s)
     * - php artisan test --filter=AuthTest
     * - php artisan test --filter=test_api_product_invalid_store_returns_error
     */

    /**
     * *if you don't know what features you need to test first, there is the quote for help:
     * -- "Look for routes, and ask question - what if this route is crashed, will be I'm fired or Financial loss, if the answer is Yes, then create test them First!"
     *
     * 3 things that you need test first:
     * - 1. Main page is loaded successful - status 200, no 500 status or something like that
     * - 2. Authentication and authorization
     * - 3. Anything related to payments
     */

    /**
     * There is no need to cover packages that you are implementing to your app
     * - because of authors of these packages are already done it before you
     * - if no, there are many doubts should you use it then
     * - we must look for seriousness of this package - valid to use or no
     */

    /**
     * The recommendation is to start test from sad path(s), bad scenarios, unhappy situations - then write happy path(s)
     */

    /**
     * What's next: Mocking, Practical Examples, Faking Scenarios: Send Email, Queue, External APIs, Events, Services
     * * Advanced level
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
