<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_to_products()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('passw%$ord1234'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'passw%$ord1234',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/products');
    }

    public function test_unauthenticated_user_cannot_access_products_page(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
