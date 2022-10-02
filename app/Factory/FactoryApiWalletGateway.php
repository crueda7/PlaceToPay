<?php

namespace App\Factory;

use Illuminate\Http\JsonResponse;

abstract class FactoryApiWalletGateway
{
    abstract public function createRequestGateway(): GatewayApiWallet;

    abstract public function getRequestRequestGateway(): GatewayApiWallet;

    public function connect(): JsonResponse
    {
        $api = $this->createRequestGateway();
        $request = $api->createRequest();
        return $api->getBodyResponse($request);
    }

    public function requestStatus(): JsonResponse
    {
        $api = $this->getRequestRequestGateway();
        $request = $api->getRequestInformation();
        return $api->getBodyResponse($request);
    }
}
