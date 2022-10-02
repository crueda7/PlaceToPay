<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HelperWebCheckout
{
    private string $locale, $endPointBase, $endpointSession;
    private HelperRequest $helperRequest;

    function __construct(Model $order)
    {
        $this->endPointBase = config('app.BASE_ENDPOINT');
        $this->endpointSession = config('app.CREATE_REQUEST');
        $this->locale = config('app.LOCALE');
        $this->helperRequest = new HelperRequest($order);
    }

    public function endpointSession(): string
    {
        return $this->endPointBase . $this->endpointSession;
    }

    public function bodyRequest(): array
    {
        return $this->helperRequest->bodyRequest($this->locale);
    }

    public function bodyInformationRequest(): array
    {
        return $this->helperRequest->bodyRequestInformation();
    }

}
