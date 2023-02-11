<?php

namespace App\Services;

use App\Jobs\UnstableItemExist;
use App\Repositories\OrderProductRepository;
use App\Repositories\StockRepository;

class StockService
{
    private StockRepository $stockRepository;
    private OrderProductRepository $orderProductRepository;

    public function __construct(StockRepository $stockRepository, OrderProductRepository $orderProductRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function decreaseStock($order): void
    {
        $items = $this->orderProductRepository->getIngredientsByOrderId($order->id)->toArray();
        foreach ($items as $item) {
            $newQuantity = $item['current_quantity'] - $item['order_item_ingredient'];
            if ($this->isItemNotStable($item, $newQuantity)) {
                UnstableItemExist::dispatch($item);
            }
            $this->stockRepository->updateCurrentQuantityById($item['id'], $newQuantity);
        }
    }

    public function isItemNotStable(array $item, float $newQuantity): bool
    {
        $fiftyPercentageQuantity = $item['initial_quantity'] / 2;
        $currentQuantity = $item['current_quantity'];
        return $currentQuantity > $fiftyPercentageQuantity && $newQuantity < $fiftyPercentageQuantity;
    }
}
