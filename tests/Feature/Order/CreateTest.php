<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShoppingCart;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected User $user;
    protected ShoppingCart $shoppingCart;
    protected Order $order;
    protected OrderDetail $orderDetail;

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

        $status = array("PENDING", "REJECTED", "OK", "ERROR", "APPROVED");
        $this->order = Order::create([
            'customer_name' => fake()->firstName(),
            'customer_email' => fake()->email(),
            'customer_mobile' => fake()->phoneNumber(),
            'status' => sort($status),
            'requestId' => fake()->numberBetween($min = 1, $max = 999999),
            'processUrl' => fake()->url(),
            'user_id' => $this->user->id,
        ]);

        $this->orderDetail = OrderDetail::create([
            'quantity' => 1,
            'product_id' => 1,
            'order_id' => $this->order->id,
        ]);
    }

    public function test_the_application_returns_a_order(): void
    {
        $this->withoutExceptionHandling();
        $orderDetails = OrderDetail::where('order_id', '=', $this->order->id)->get();

        $this->actingAs($this->user)->get(route('orders.create'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Order/Order')
                ->has('cart', count($orderDetails))
            );
    }

    public function test_authenticated_user_can_access_create_order(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->get('/orders');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_user_cannot_access_create_order(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect(route('login'));
    }
}
