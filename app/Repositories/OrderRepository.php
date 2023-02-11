<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function initiate()
    {
        return $this->model->create(['table_num' => rand(1, 10)]);
    }
}
