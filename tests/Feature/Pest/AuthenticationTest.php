<?php


test('unauthenticated user cannot access products', function(){
    $this->get('/products')
        ->assertRedirect('/login')
        ->assertStatus(302);
});


test('unauthenticated user cannot access products page - short format')
    ->get('/products')
    ->assertStatus(302)
    ->assertRedirect('login');
