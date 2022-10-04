<?php

namespace App\Factory;

use Illuminate\Http\JsonResponse;

interface GatewayApiWallet
{
    public function createRequest(): object;

    public function getBodyResponse(object $request): JsonResponse;

    public function getRequestInformation(): object;
}
