<?php

namespace App\Helpers;

use App\Constants\StatusOrders;
use App\Models\Order;

class WalletResponseHelper
{
    public static function getResponse(array $jsonObject, ?int $orderId, ?string $endPoint, ?string $requestId): \Illuminate\Http\JsonResponse
    {
        $statusCode = ($jsonObject['status']) ? $jsonObject['status']['status'] : 'ERROR';
        $status = constant("App\Constants\StatusOrders::{$statusCode}");


        $order = Order::findOrFail($orderId);
        $order->status = $status->name;
        $order->message = $jsonObject['status']['message'];

        switch ($status) {
            case StatusOrders::OK:
            {
                $order->processUrl = ($jsonObject['status']) ? $jsonObject['processUrl'] : '';
                $order->requestId = $jsonObject['requestId'];
                break;
            }
            case StatusOrders::APPROVED:
            case StatusOrders::PENDING:
            {
                $order->processUrl = $endPoint . '/' . $requestId;
                $order->requestId = $jsonObject['requestId'];
                break;
            }
            case StatusOrders::REJECTED:
            {
                $order->requestId = $jsonObject['requestId'];
                break;
            }
            default:
            {
                $jsonObject['status']['message'] = ($status == StatusOrders::ERROR) ? 'Whops, looks like something went wrong error' : 'Error try again later';
                break;
            }
        }
        $order->save();

        return response()->json($jsonObject, $status->status());
    }
}
