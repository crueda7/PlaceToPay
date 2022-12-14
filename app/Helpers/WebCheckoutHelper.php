<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class WebCheckoutHelper
{
    private string $locale, $endPointBase, $endpointSession;
    private Model $order;
    private RequestHelper $helperRequest;

    function __construct(Model $order)
    {
        $this->endPointBase = config('app.BASE_ENDPOINT');
        $this->endpointSession = config('app.CREATE_REQUEST');
        $this->locale = config('app.LOCALE');
        $this->order = $order;
    }

    public function endpointSession(): string
    {
        return $this->endPointBase . $this->endpointSession;
    }

    public function bodyRequest(): array
    {
        $helperRequest = new RequestHelper($this->order);
        return $helperRequest->bodyRequest($this->locale);
    }

    public function bodyInformationRequest(): array
    {
        $helperRequest = new RequestHelper($this->order);
        return ['auth' => $helperRequest->bodyRequestInformation()];
    }

}
