<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("db:seed", ['--class' => 'ProductSeeder']);
        $this->user = User::factory()->create();
    }

    public function test_the_application_returns_a_list_products(): void
    {
        $this->withoutExceptionHandling();
        $rows = Product::all();
        $this->actingAs($this->user)->get('/product')
            ->assertInertia(fn(Assert $page) => $page
                ->component('Product/Product')
                ->has('products', count($rows))
            );
    }

    public function test_authenticated_user_can_access_list_products(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)->get('/product');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_guest_user_cannot_access_list_products(): void
    {
        $response = $this->get('/product');
        $response->assertRedirect(route('login'));
    }
}
