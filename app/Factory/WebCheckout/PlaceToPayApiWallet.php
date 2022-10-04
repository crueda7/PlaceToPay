<?php

namespace App\Factory\WebCheckout;

use App\Factory\GatewayApiWallet;
use App\Helpers\WalletResponse;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

class PlaceToPayApiWallet implements GatewayApiWallet
{
    private array $body;
    private string $endPoint;
    private ?int $orderId;
    private ?string $requestId;
    private Client $client;

    public function __construct(array $body, string $endPoint, ?int $orderId, ?string $requestId)
    {
        $this->body = $body;
        $this->endPoint = $endPoint;
        $this->orderId = $orderId;
        $this->requestId = $requestId;
        $this->client = new Client(['headers' => ['Accept' => 'application/json']]);
    }

    public function createRequest(): object
    {
        return $this->client->request('POST', $this->endPoint, ['json' => $this->body]);
    }

    public function getBodyResponse(object $request): JsonResponse
    {
        $bodyResponse = json_decode($request->getBody(), true);

        return WalletResponse::getResponse($bodyResponse, $this->orderId, $this->endPoint, $this->requestId);
    }

    public function getRequestInformation(): object
    {
        return $this->client->request('POST', $this->endPoint . '/' . $this->requestId, ['json' => $this->body]);
    }
}
