<?php

namespace App\Factory\WebCheckout;

use App\Factory\GatewayApiWallet;
use App\Helpers\HelperWebCheckout;
use Illuminate\Database\Eloquent\Model;

class PlaceToPayFactory extends \App\Factory\FactoryApiWalletGateway
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
        $helper = new HelperWebCheckout($this->order);

        list ($body, $orderId) = $helper->bodyRequest();

        return new PlaceToPayApiWallet($body, $helper->endpointSession(), $orderId, null);
    }

    public function getRequestRequestGateway(): GatewayApiWallet
    {
        $helper = new HelperWebCheckout($this->order);

        $body = $helper->bodyInformationRequest();

        return new PlaceToPayApiWallet($body, $helper->endpointSession(), $this->orderId, $this->requestId);
    }
}
