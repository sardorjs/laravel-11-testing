<?php

/**
 * TDD - Test Driven Design means to write test before writing code itself
 * * p.s. write algorithm of user journey map - if else and etc.
 * * write all possible scenarios
 *
 * Cycle: red, green, refactor
 * * red - early test to be failed
 * * green - write code so test would be successful
 * * refactor - code so it would be well written
 */

/**
 * What's next: Mocking, Practical Examples, Faking Scenarios: Send Email, Queue, External APIs, Events, Services
 * * Advanced level
 */

beforeEach(function(){
    $this->user = createUser();
    $this->admin = createUser(true);
});

test('unauthenticated user cannot access products page', function () {
    $this->get('/tdd/products')
        ->assertRedirect('/login');
});


test('homepage contains empty table', function (){
    $this->actingAs($this->user)
        ->get('/tdd/products')
        ->assertStatus(200)
        ->assertSee('No products found');
});

test('homepage contains non empty table', function (){
    $product = \App\Models\Product::factory()->create();

    $this->actingAs($this->user)
        ->get('/tdd/products')
        ->assertStatus(200)
        ->assertDontSee('No products found')
        ->assertSee($product->title)
        ->assertViewHas('products', function ($collection) use ($product){
            return $collection->contains($product);
        });

});
