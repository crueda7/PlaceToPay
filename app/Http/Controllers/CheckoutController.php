<?php

namespace App\Http\Controllers;

use App\Constants\AppConfig;
use App\Factory\FactoryApiWalletGateway;
use App\Helpers\ControllerHelper;
use App\Helpers\ResponseHelper;
use App\Interfaces\OrderRepository;
use App\Models\Order;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Order $order
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function create(Order $order): JsonResponse
    {
        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, null, null, AppConfig::GATEWAY]);

        return ControllerHelper::encodeJsonResponse($paymentGateway->connect());
    }

    /**
     * @param Request $request
     * @return string|false
     * @throws BindingResolutionException
     */
    public function tryAgain(Request $request)
    {
        $validator = ControllerHelper::validateRequest($request, ['order_id' => 'required|integer',]);

        if ($validator->fails()) {
            return ControllerHelper::encodeStringResponse(ResponseHelper::Error($validator->errors()->first()));
        }

        $order = $this->orderRepository->orderById($validator->getData()['order_id']);

        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, null, $order->id, AppConfig::GATEWAY]);

        return ControllerHelper::encodeJsonResponse($paymentGateway->connect());
    }

    /**
     * @param Request $request
     * @return bool|string
     * @throws BindingResolutionException
     */

    function retry(Request $request)
    {
        $validator = ControllerHelper::validateRequest($request, ['order_id' => 'required|integer',]);

        if ($validator->fails()) {
            return ControllerHelper::encodeStringResponse(ResponseHelper::Error($validator->errors()->first()));
        }

        $order = $this->orderRepository->orderById($validator->getData()['order_id']);

        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, $order->requestId, $order->id, AppConfig::GATEWAY]);

        return ControllerHelper::encodeJsonResponse($paymentGateway->requestStatus());
    }
}
