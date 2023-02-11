<?php

namespace Tests\Unit;

use App\Enums\OrderEnum;
use App\Exceptions\UnableToCreateOrder;
use App\Models\ProductIngredient;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;
    protected $productIngredient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = app(OrderService::class);
        $this->productIngredient = ProductIngredient::factory()->create(['kg_quantity' => 0.1]);
    }

    public function test_it_can_create_order()
    {
        $payload = [
            [
                "product_id" => $this->productIngredient->product_id,
                "quantity" => 2,
            ]
        ];
        $order = $this->orderService->create($payload);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderEnum::STATUS_PLACED
        ]);
        $this->assertDatabaseHas('order_products', [
            "order_id" => $order->id,
            "product_id" => $this->productIngredient->product_id,
            "quantity" => 2,
        ]);
    }

    public function test_it_throw_exception_if_product_is_invalid_and_rollback_order_creation()
    {
        $this->expectException(UnableToCreateOrder::class);

        $payload = [
            [
                "product_id" => 9999999,
                "quantity" => 2,
            ]
        ];
        $this->orderService->create($payload);

        $this->assertDatabaseCount('orders', 0);
    }
}
