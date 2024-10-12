<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    //* before launching - change credentials in ./phpunit.xml file or .env.testing
    use RefreshDatabase; // always refresh migrate when launching tests

    /**
     * AAA "Arrange, Act, Assert" - Automated Testing - every function - must have these 3 things
     * 1) Arrange - means prepare scenario - create all need credentials, info, data to work with
     * 2) Act - calling some api, route, url, method - simulate user behavior
     * 3) Assert - tests, assert status etc.
     *
     * * make test for each scenario
     */

    public function test_products_page_contains_empty_table(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_products_page_contains_non_empty_table(): void
    {
        $product = Product::create([
            'name' => "Product 2",
            'price' => 1234
        ]);
        $response = $this->get('/products');

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

        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function ($collection) use ($product) {
           return !$collection->contains($product);
        });
    }


    public function test_paginated_products_table_doesnt_contain_11th_record_via_factory(): void
    {
        $products = Product::factory()->count(11)->create();
        $lastProduct = $products->last();

        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

}
