<?php

namespace App\Http\Controllers;

use App\Factory\FactoryApiWalletGateway;
use App\Helpers\ResponseHelper;
use App\Models\Order;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    protected string $wallet = 'placetopay';

    /**
     * @param Order $order
     * @param string $wallet
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function create(Order $order, string $wallet): JsonResponse
    {
        /**
         * Cambiar el ultimo parametro 'mock' por $wallet
         */
        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, null, null, 'mock']);

        $response = $paymentGateway->connect();

        return response()->json($response->getData());
    }

    /**
     * @param Request $request
     * @return string|false
     * @throws BindingResolutionException
     */
    public function tryAgain(Request $request)
    {
        /**
         * Cambiar el ultimo parametro 'mock' por $this->wallet
         */
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return json_encode(ResponseHelper::response('2',$validator->errors()->first(),[]));
        }

        $order = Order::find($validator->getData()['order_id']);

        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, null, null, 'mock']);

        $response = $paymentGateway->connect();

        return response()->json($response->getData());
    }

    /**
     * @param Request $request
     * @return bool|string
     * @throws BindingResolutionException
     */

    function retry(Request $request)
    {
        /**
         * Cambiar el ultimo parametro 'mock' por $this->wallet
         */
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return json_encode(ResponseHelper::response('2', $validator->errors()->first(), []));
        }

        $order = Order::find($validator->getData()['order_id']);

        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, $order->requestId, $order->id, 'mock']);

        $response = $paymentGateway->requestStatus();

        return response()->json($response->getData());
    }
}
