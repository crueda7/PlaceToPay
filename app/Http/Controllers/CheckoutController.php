<?php

namespace App\Http\Controllers;

use App\Factory\FactoryApiWalletGateway;
use App\Models\Order;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function create(Order $order, string $wallet): \Illuminate\Http\JsonResponse
    {
        /**
         * Cambiar el ultimo parametro 'mock' por $wallet
         */
        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, null, null, 'mock']);

        $response = $paymentGateway->connect();

        return response()->json($response->getData());
    }

    function getInformation(int $orderId, string $wallet): View
    {
        /**
         * Cambiar el ultimo parametro 'mock' por $wallet
         */

        $order = Order::find($orderId);

        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [null, $order->requestId, $orderId, 'mock']);

        $paymentGateway->requestStatus();


        return view('products.index');
    }
}
