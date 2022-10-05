<?php

namespace App\Factory\Mock;

use App\Factory\GatewayApiWallet;
use App\Helpers\WebCheckoutHelper;
use Illuminate\Database\Eloquent\Model;

class MockPayFactory extends \App\Factory\FactoryApiWalletGateway
{
    private ?Model $order;
    private ?string $requestId;
    private ?int $orderId;

    public function __construct(?Model $order, ?string $requestId, ?int $orderId)
    {
        $this->order = $order;
        $this->requestId = $requestId;
        $this->orderId = $orderId;
    }

    public function createRequestGateway(): GatewayApiWallet
    {
        $helper = new WebCheckoutHelper($this->order);

        list ($body, $orderId) = $helper->bodyRequest();

        return new MockApiWallet($body, $orderId, $this->requestId);
    }

    public function getRequestRequestGateway(): GatewayApiWallet
    {
        $helper = new WebCheckoutHelper($this->order);

        $body = $helper->bodyInformationRequest();

        return new MockApiWallet($body, $this->orderId, $this->requestId);
    }
}
