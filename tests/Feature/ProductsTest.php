<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    //* before launching - change credentials in ./phpunit.xml file or .env.testing
    use RefreshDatabase; // always refresh migrate when launching tests

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
        $this->admin = $this->createUser(isAdmin: true);
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin,
        ]);
    }

    public function test_products_page_contains_empty_table(): void
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_products_page_contains_non_empty_table(): void
    {
        $product = Product::create([
            'name' => "Product 2",
            'price' => 1234
        ]);
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products found');
        $response->assertSee('Product 2');
        $response->assertViewHas('products', function ($collection) use ($product) {
           return $collection->contains($product);
        });
    }


    public function test_paginated_products_table_doesnt_contain_11th_record(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            $product = Product::create([
                'name' => "Product $i",
                'price' => rand(1, 999)
            ]);
        }

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function ($collection) use ($product) {
           return !$collection->contains($product);
        });
    }


    public function test_paginated_products_table_doesnt_contain_11th_record_via_factory(): void
    {
        $products = Product::factory()->count(11)->create();
        $lastProduct = $products->last();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button(): void
    {
        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_user_cannot_see_products_create_button(): void
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }


    public function test_admin_can_access_product_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_product_create_page(): void
    {
        $response = $this->actingAs($this->user)->get('/products/create');

        $response->assertStatus(403);
    }

    public function test_create_product_successful(): void
    {
        $product = [
            'name' => 'Product 2',
            'price' => 1234
        ];
        $response = $this->actingAs($this->admin)->post('/products', $product);

        $response->assertStatus(302);
        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::query()->latest()->first();

        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    public function test_product_edit_contains_correct_values(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get('products/'. $product->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('value="'.$product->name.'"', false);
        $response->assertSee('value="'.$product->price.'"', false);
        $response->assertViewHas('product', $product);
    }

    public function test_product_update_validation_redirects_back_to_form(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->put('/products/'.$product->id, [
            'name' => '',
            'price' => 0,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'price']);
        $response->assertInvalid(['name', 'price']);
    }

    public function test_product_delete_successful()
    {
        $product = Product::factory()->create();

        $this->assertCount(1, Product::query()->get());

        $response = $this->actingAs($this->admin)->delete('products/'. $product->id);

        $response->assertStatus(302);
        $response->assertRedirect('/products');

        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);

    }


}
