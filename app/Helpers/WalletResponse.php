<?php

namespace App\Helpers;

use App\Constants\StatusOrders;
use App\Models\Order;

class WalletResponse
{
    public static function getResponse(array $jsonObject, ?int $orderId, ?string $endPoint, ?string $requestId): \Illuminate\Http\JsonResponse
    {
        $statusCode = ($jsonObject['status']) ? $jsonObject['status']['status'] : 'ERROR';
        $status = constant("App\Constants\StatusOrders::{$statusCode}");

        $response = array();
        foreach ($jsonObject as $key => $value)
            $response[$key] = $value;
        $response['message'] = $status->name;
        $response['status'] = $status->status();

        $order = Order::findOrFail($orderId);
        $order->status = $status->name;

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
                $response['message'] = ($status == StatusOrders::ERROR) ? 'Whops, looks like something went wrong error' : 'Error try again later';
                break;
            }
        }
        $order->save();

        return response()->json($response, $status->status());
    }
}
