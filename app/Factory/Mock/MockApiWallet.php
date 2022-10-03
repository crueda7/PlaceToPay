<?php

namespace App\Factory\Mock;

use App\Constants\StatusOrders;
use App\Factory\GatewayApiWallet;
use App\Helpers\WalletResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class MockApiWallet implements GatewayApiWallet
{
    private array $body;
    private string $endPoint;
    private ?int $orderId;
    private ?string $requestId;

    public function __construct(array $body, ?int $orderId, ?string $requestId)
    {
        $this->body = $body;
        $this->endPoint = 'fake.endpoint';
        $this->orderId = $orderId;
        $this->requestId = $requestId;
    }

    public function createRequest(): object
    {
        return (object)$this->body;
    }

    public function getBodyResponse(object $request): JsonResponse
    {
        return WalletResponse::getResponse($this->getFakeBodyResponse(), $this->orderId, $this->endPoint, $this->requestId);
    }

    public function getRequestInformation(): object
    {
        return WalletResponse::getResponse($this->getFakeInformationResponse(), $this->orderId, $this->endPoint, $this->requestId);
    }

    /**
     * @throws Exception
     */
    private function getFakeBodyResponse(): array
    {
        $options = ['ERROR', 'OK', 'APPROVED', 'REJECTED', 'PENDING'];
        $status = constant("App\Constants\StatusOrders::{$options[rand(0, count($options) - 1)]}");
        $requestId = random_int(10, 9999999);
        return match ($status) {
            StatusOrders::ERROR, StatusOrders::REJECTED => [
                'status' => [
                    'status' => $status->name,
                    'reason' => 'Reason fake',
                    'message' => 'Message fake',
                    'date' => date('c'),
                ],
                'requestId' => 100100,
                'processUrl' => 'checkout/fake/url'
            ],
            StatusOrders::OK, StatusOrders::APPROVED, StatusOrders::PENDING => [
                'status' => [
                    'status' => $status->name,
                    'reason' => 'Reason fake',
                    'message' => 'Message fake',
                    'date' => date('c'),
                ],
                'requestId' => $requestId,
                'processUrl' => "checkout/fake/url/{$requestId}",
            ],
        };
    }

    private function getFakeInformationResponse(): array
    {
        //$options = ['ERROR', 'REJECTED', 'PENDING'];
        $options = ['ERROR', 'OK', 'APPROVED', 'REJECTED', 'PENDING'];
        $status = constant("App\Constants\StatusOrders::{$options[rand(0, count($options) - 1)]}");
        return [
            'processUrl' => "checkout/retry/fake/url/$this->requestId",
            "requestId" => $this->requestId,
            "status" => [
                "status" => $status->name,
                'reason' => 'Reason fake',
                'message' => 'Message fake',
                "date" => date('c'),
            ],
            "request" => [
                "locale" => "es_CO",
                "payer" => [
                    "document" => "1122334455",
                    "documentType" => "CC",
                    "name" => "John",
                    "surname" => "Doe",
                    "company" => "Evertec",
                    "email" => "johndoe@app.com",
                    "mobile" => "+5731111111111",
                    "address" => [
                        "street" => "Calle falsa 123",
                        "city" => "Medellín",
                        "state" => "Poblado",
                        "postalCode" => "55555",
                        "country" => "Colombia",
                        "phone" => "+573111111111"
                    ]
                ],
                "buyer" => [
                    "document" => "1122334455",
                    "documentType" => "CC",
                    "name" => "John",
                    "surname" => "Doe",
                    "company" => "Evertec",
                    "email" => "johndoe@app.com",
                    "mobile" => "+5731111111111",
                    "address" => [
                        "street" => "Calle falsa 123",
                        "city" => "Medellín",
                        "state" => "Poblado",
                        "postalCode" => "55555",
                        "country" => "Colombia",
                        "phone" => "+573111111111"
                    ]
                ],
                "payment" => [
                    "reference" => "12345",
                    "description" => "Prueba de pago",
                    "amount" => [
                        "currency" => "COP",
                        "total" => 2000,
                        "taxes" => [
                            [
                                "kind" => "valueAddedTax",
                                "amount" => 1000,
                                "base" => 0
                            ]
                        ],
                        "details" => [
                            [
                                "kind" => "discount",
                                "amount" => 1000
                            ]
                        ]
                    ],
                    "allowPartial" => false,
                    "shipping" => [
                        "document" => "1122334455",
                        "documentType" => "CC",
                        "name" => "John",
                        "surname" => "Doe",
                        "company" => "Evertec",
                        "email" => "johndoe@app.com",
                        "mobile" => "+5731111111111",
                        "address" => [
                            "street" => "Calle falsa 123",
                            "city" => "Medellín",
                            "state" => "Poblado",
                            "postalCode" => "55555",
                            "country" => "Colombia",
                            "phone" => "+573111111111"
                        ]
                    ],
                    "items" => [
                        [
                            "sku" => "12345",
                            "name" => "product_1",
                            "category" => "physical",
                            "qty" => "1",
                            "price" => 1000,
                            "tax" => 0
                        ]
                    ],
                    "fields" => [
                        [
                            "keyword" => "_test_field_value_",
                            "value" => "_test_field_",
                            "displayOn" => "approved"
                        ]
                    ],
                    "recurring" => [
                        "periodicity" => "D",
                        "interval" => "1",
                        "nextPayment" => "2019-08-24",
                        "maxPeriods" => 1,
                        "dueDate " => "2019-09-24",
                        "notificationUrl " => "http:checkout.placetopay.com"
                    ],
                    "subscribe" => false,
                    "dispersion" => [
                        [
                            "agreement" => "1299",
                            "agreementType" => "MERCHANT",
                            "amount" => [
                                "currency" => "USD",
                                "total" => 200
                            ]
                        ]
                    ],
                    "modifiers" => [
                        [
                            "type" => "FEDERAL_GOVERNMENT",
                            "code" => 17934,
                            "additional" => [
                                "invoice" => "123345"
                            ]
                        ]
                    ]
                ],
                "subscription" => [
                    "reference" => "12345",
                    "description" => "Ejemplo de descripción",
                    "fields" => [
                        "keyword" => "1111",
                        "value" => "lastDigits",
                        "displayOn" => "none"
                    ]
                ],
                "fields" => [
                    [
                        "keyword" => "_processUrl_",
                        "value" => "https =>//checkout.redirection.test/session/1/a592098e22acc709ec7eb30fc0973060",
                        "displayOn" => "none"
                    ]
                ],
                "paymentMethod" => "visa",
                "expiration" => "2019-08-24T14 =>15 =>22Z",
                "returnUrl" => "https =>//commerce.test/return",
                "cancelUrl" => "https =>//commerce.test/cancel",
                "ipAddress" => "127.0.0.1",
                "userAgent" => "PlacetoPay Sandbox",
                "skipResult" => false,
                "noBuyerFill" => false,
                "type" => "checkin"
            ],
            "payment" => [
                [
                    "status" => [
                        "status" => "APPROVED",
                        "reason" => "00",
                        "message" => "La petición ha sido aprobada exitosamente",
                        "date" => "2022-07-27T14 =>51 =>27-05 =>00"
                    ],
                    "internalReference" => 12345,
                    "reference" => "12345",
                    "paymentMethod" => "visa",
                    "paymentMethodName" => "Visa",
                    "issuerName" => "JPMORGAN CHASE BANK, N.A.",
                    "amount" => [
                        "from" => [
                            "currency " => "COP",
                            "total " => 10000
                        ],
                        "to" => [
                            "currency " => "COP",
                            "total " => 10000
                        ],
                        "factor" => 1
                    ],
                    "receipt" => "052617800175",
                    "franchise" => "PS_VS",
                    "refunded" => false,
                    "authorization" => "965960",
                    "processorFields" => [
                        [
                            "keyword" => "1111",
                            "value" => "lastDigits",
                            "displayOn" => "none"
                        ]
                    ],
                    "dispersion" => null,
                    "agreement" => null,
                    "agreementType" => null,
                    "discount" => [
                        "base" => 3000,
                        "code" => "17934",
                        "type" => "FRANCHISE",
                        "amount" => 1000
                    ],
                    "subscription" => null
                ]
            ],
            "subscription" => [
                "status" => [
                    "status" => "OK",
                    "reason" => "00",
                    "message" => "La petición ha sido aprobada exitosamente",
                    "date" => "2022-07-27T14 =>51 =>27-05 =>00"
                ],
                "type" => "token",
                "instrument" => [
                    [
                        "keyword" => "token",
                        "value" => "a3bfc8e2afb9ac5583922eccd6d2061c1b0592b099f04e352a894f37ae51cf1a",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "subtoken",
                        "value" => "8740257204881111",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "franchise",
                        "value" => "visa",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "franchiseName",
                        "value" => "Visa",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "issuerName",
                        "value" => "JPMORGAN CHASE BANK, N.A.",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "lastDigits",
                        "value" => "1111",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "validUntil",
                        "value" => "2029-12-31",
                        "displayOn" => "none"
                    ],
                    [
                        "keyword" => "installments",
                        "value" => null,
                        "displayOn" => "none"
                    ]
                ]
            ]
        ];
    }
}
