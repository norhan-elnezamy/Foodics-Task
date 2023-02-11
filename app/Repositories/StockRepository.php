<?php

namespace App\Repositories;

use App\Models\StockItem;

class StockRepository extends BaseRepository
{
    public function __construct(StockItem $stock)
    {
        $this->model = $stock;
    }

    public function updateCurrentQuantityById($id, $quantity)
    {
        $this->model->where('id', $id)->update([
            'current_kg_quantity' => $quantity
        ]);
    }

}
