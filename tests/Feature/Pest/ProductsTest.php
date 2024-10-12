<?php

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(true);
});

test('homepage contains empty table', function () {
    $this->actingAs($this->user)
        ->get('/products')
        ->assertStatus(200)
        ->assertSee('No products found');
});


test('homepage contains non empty table', function(){
    $product = \App\Models\Product::create([
        'name' => 'Product 1',
        'price' => 100,
    ]);

    $this->actingAs($this->user)
        ->get('products')
        ->assertStatus(200)
        ->assertDontSee('No products found')
        ->assertSee($product->name)
        ->assertViewHas('products', function($products) use ($product){
            return $products->contains($product);
        });
});

test('create product successful', function(){

    $product = [
      'name' => 'Product 123',
      'price' => 123,
    ];

    $this->actingAs($this->admin)
        ->post('/products', $product)
        ->assertStatus(302)
        ->assertRedirect('/products');

    $this->assertDatabaseHas('products', $product);

    $lastProduct = \App\Models\Product::query()->latest()->first();

    expect($lastProduct->name)->toBe($product['name'])
        ->and($lastProduct->price)->toBe($product['price']);
});
