<?php

namespace App\Repositories;

use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderProductRepository extends BaseRepository
{
    public function __construct(OrderProduct $orderProduct)
    {
        $this->model = $orderProduct;
    }

    public function getIngredientsByOrderId($orderId)
    {
        return $this->model->join('product_ingredients as ing', 'ing.product_id', 'order_products.product_id')
            ->join('stock_items as stocks', 'stocks.id', 'ing.stock_item_id')
            ->where('order_products.order_id', $orderId)
            ->select(
                'stocks.id',
                'stocks.name',
                'stocks.initial_kg_quantity as initial_quantity',
                'stocks.current_kg_quantity as current_quantity',
                DB::raw('SUM(ing.kg_quantity * order_products.quantity) as order_item_ingredient'),
            )->groupBy('stocks.id')->get();
    }
}
