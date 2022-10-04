<?php

namespace Tests\Feature\ShoppingCart;

use App\Models\ShoppingCart;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    protected User $user;
    protected ShoppingCart $shoppingCart;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("db:seed", ['--class' => 'ProductSeeder']);
        $this->user = User::factory()->create();

        $this->shoppingCart = ShoppingCart::create([
            'user_id' => $this->user->id,
            'product_id' => 1,
            'quantity' => 1,
        ]);
    }

    public function test_the_application_destroy_shopping_cart(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->user)->delete(route('shoppingCarts.destroy', $this->shoppingCart->id));

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
