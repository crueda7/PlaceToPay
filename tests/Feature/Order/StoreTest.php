<?php

namespace Tests\Feature\Order;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_the_application_store_order(): void
    {
        $this->withoutExceptionHandling();

        $status = array("PENDING", "REJECTED", "OK", "ERROR", "APPROVED");
        $response = $this->actingAs($this->user)->post(route('orders.store'), [
            'customer_name' => fake()->firstName(),
            'customer_email' => fake()->email(),
            'customer_mobile' => fake()->phoneNumber(),
            'status' => sort($status),
            'requestId' => fake()->numberBetween($min = 1, $max = 999999),
            'processUrl' => fake()->url(),
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_authenticated_user_can_access_store_order(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->get('/orders');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_user_cannot_access_store_order(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect(route('login'));
    }
}
