<?php

namespace Tests\Feature\ShoppingCart;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("db:seed", ['--class' => 'ProductSeeder']);
        $this->user = User::factory()->create();
    }

    public function test_the_application_store_shopping_cart(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->user)->post('/shoppingCarts', [
            'user_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_authenticated_user_can_access(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->get('/shoppingCarts');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_user_cannot_access(): void
    {
        $response = $this->get('/shoppingCarts');
        $response->assertRedirect(route('login'));
    }
}
