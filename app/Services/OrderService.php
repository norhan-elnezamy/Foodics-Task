<?php

namespace App\Services;

use App\Exceptions\UnableToCreateOrder;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $orderProducts
     * @return Order
     * @throws UnableToCreateOrder
     */
    public function create(array $orderProducts): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->initiate();
            $order->orderProducts()->createMany($orderProducts);

            DB::commit();

            return $order;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new UnableToCreateOrder($exception->getMessage());
        }
    }
}
