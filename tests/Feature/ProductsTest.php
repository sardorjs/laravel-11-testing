<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{

    public function test_products_page_contains_empty_table(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_products_page_contains_none_empty_table(): void
    {
        Product::create([
            'name' => "Product 1",
            'price' => 1234
        ]);
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Product 1');
    }

}
