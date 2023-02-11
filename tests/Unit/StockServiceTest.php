<?php

namespace Tests\Unit;

use App\Jobs\UnstableItemExist;
use App\Models\Order;
use App\Models\ProductIngredient;
use App\Models\StockItem;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $stockService;
    protected $stockItem;
    protected $order;
    protected $productIngredient;


    protected function setUp(): void
    {
        parent::setUp();
        $this->stockService = app(StockService::class);
    }

    public function test_it_can_decrease_stable_stock_item()
    {
        Queue::fake();
        $this->seedProductIngredient();
        $productQuantity = rand(1, 4);
        $this->seedOrder($productQuantity);

        $this->stockService->decreaseStock($this->order);

        $itemTotalIngredient = $this->productIngredient->kg_quantity * $productQuantity;
        $this->assertDatabaseHas('stock_items', [
            'current_kg_quantity' => $this->stockItem->current_kg_quantity - $itemTotalIngredient
        ]);
        Queue::assertNotPushed(UnstableItemExist::class);
    }

    public function test_it_can_decrease_unstable_stock_item_and_send_email()
    {
        Queue::fake();

        $this->seedProductIngredient();
        $productQuantity = rand(6, 10);
        $this->seedOrder($productQuantity);

        $this->stockService->decreaseStock($this->order);

        $itemTotalIngredient = $this->productIngredient->kg_quantity * $productQuantity;
        $this->assertDatabaseHas('stock_items', [
            'current_kg_quantity' => $this->stockItem->current_kg_quantity - $itemTotalIngredient
        ]);

        Queue::assertPushed(UnstableItemExist::class);
    }

    public function test_it_send_one_email_if_stock_item_still_fell_precariously()
    {
        Queue::fake();

        $this->seedProductIngredient();

        $this->seedOrder(7);
        $this->stockService->decreaseStock($this->order);

        $this->seedOrder(2);
        $this->stockService->decreaseStock($this->order);

        Queue::assertPushed(UnstableItemExist::class, 1);
    }

    public function test_is_item_become_unstable_for_first_time()
    {
        $itemDetails = [
            'name' => 'cheese',
            'initial_quantity' => 100,
            'current_quantity' => 60
        ];
        $newQuantity = rand(1, 48);
        $result = $this->stockService->isItemNotStable($itemDetails, $newQuantity);

        $this->assertEquals(true, $result);
    }

    public function test_is_item_stable()
    {
        $itemDetails = [
            'name' => 'cheese',
            'initial_quantity' => 100,
            'current_quantity' => 90
        ];
        $newQuantity = rand(50, 89);
        $result = $this->stockService->isItemNotStable($itemDetails, $newQuantity);

        $this->assertEquals(false, $result);
    }

    public function test_it_can_trigger_unstable_item_only_one_time()
    {
        $itemDetails = [
            'name' => 'cheese',
            'initial_quantity' => 100,
            'current_quantity' => 40
        ];
        $newQuantity = rand(1, 39);
        $result = $this->stockService->isItemNotStable($itemDetails, $newQuantity);

        $this->assertEquals(false, $result);
    }

    private function seedOrder($productQuantity)
    {
        $this->order = Order::factory()->create();
        $this->order->orderProducts()->create([
            'quantity' => $productQuantity,
            'product_id' => $this->productIngredient->product_id
        ]);
    }

    private function seedProductIngredient()
    {
        $this->stockItem = StockItem::factory()->create([
            'initial_kg_quantity' => 1,
            'current_kg_quantity' => 1
        ]);
        $this->productIngredient = ProductIngredient::factory()->create([
            'stock_item_id' => $this->stockItem->id,
            'kg_quantity' => 0.1
        ]);
    }
}
