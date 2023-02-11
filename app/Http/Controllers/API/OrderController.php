<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use function response;

class OrderController extends Controller
{
    private OrderService $orderService;
    private StockService $stockService;

    public function __construct(OrderService $orderService, StockService $stockService)
    {
        $this->orderService = $orderService;
        $this->stockService = $stockService;
    }

    /**
     * @param CreateOrderRequest $request
     * @return JsonResponse
     * @throws \App\Exceptions\UnableToCreateOrder
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->products);
        $this->stockService->decreaseStock($order);

        return response()->json([
            'statue' => true,
            'message' => 'Order Created Successfully.',
            'order' => OrderResource::make($order)
        ], 200);
    }
}
