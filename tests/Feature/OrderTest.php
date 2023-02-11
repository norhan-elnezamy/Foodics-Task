<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductIngredient;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    private $product;
    private $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create();
        $this->orderService = app(OrderService::class);
        ProductIngredient::factory()->count(3)->create(['product_id' => $this->product->id]);
    }

    /** @test */
    public function it_can_validate_request()
    {
        $payload = [
            "products" => [
                [
                    "product_id" => $this->product->id,
                ]
            ]
        ];

        $response = $this->post('api/order', $payload);
        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_store_new_order()
    {
        Mail::fake();

        $response = $this->post('api/order', [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 2,
                ]
            ]
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('order_products', [
            "product_id" => $this->product->id,
            "quantity" => 2,
        ]);
    }
}
