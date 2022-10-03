<?php

namespace Tests\Feature\ShoppingCart;

use App\Models\ShoppingCart;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
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

    public function test_the_application_returns_a_list_shopping_cart(): void
    {
        $this->withoutExceptionHandling();
        $rows = ShoppingCart::where('user_id','=',$this->user->id)->get();

        $this->actingAs($this->user)->get('/shoppingCarts')
            ->assertInertia(fn(Assert $page) => $page
                ->component('Shop/ShoppingCart')
                ->has('shoppingCarts', count($rows))
            );
    }

    public function test_authenticated_user_can_access_shopping_cart(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->get('/shoppingCarts');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_user_cannot_access_shopping_cart(): void
    {
        $response = $this->get('/shoppingCarts');
        $response->assertRedirect(route('login'));
    }
}
