<?php

namespace App\Helpers;

use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HelperRequest
{
    private string $login, $apiKey, $tranKey, $seed, $nonce;
    private Model $order;
    function __construct(Model $order)
    {
        $this->login = config('app.LOGIN');
        $this->apiKey = config('app.SECRET_KEY');
        $this->seed = date('c');
        $temp_nonce = Str::random();
        $this->tranKey = base64_encode(sha1($temp_nonce . $this->seed . $this->apiKey, true));
        $this->nonce = base64_encode($temp_nonce);
        $this->order = $order;
    }

    public function bodyRequestInformation(): array
    {
        return [
            'login' => $this->login,
            'tranKey' => $this->tranKey,
            'nonce' => $this->nonce,
            'seed' => $this->seed,
        ];
    }

    private function getBuyer(Model $order): array
    {
        return [
            [
                'document' => '1122334455',
                'documentType' => 'CC',
                'name' => $order->customer_name,
                'surname' => $order->customer_name,
                'company' => 'Evertec',
                'email' => $order->customer_email,
                'mobile' => $order->customer_mobile,
                'address' => [
                    'street' => 'Calle falsa 123',
                    'city' => 'Floridablanca',
                    'state' => 'Santander',
                    'postalCode' => '681001',
                    'country' => 'Colombia',
                    'phone' => '+573111111111',
                ]
            ]
        ];
    }

    private function getPayment(Model $order): array
    {
        return [
            'reference' => $order->id,
            'description' => 'PAGO PLACE TO PAY SHOP ONLINE',
            'amount' => [
                'currency' => 'COP',
                'total' => $this->getTotal($this->getItems($order)),
            ],
            'items' => $this->getItems($order)
        ];
    }

    private function getItems(Model $order): array
    {
        $items = [];
        $details = OrderDetail::where('order_id', $order->id)->get();
        foreach ($details as $row) {
            $product = Product::where('id', $row->product_id)->first();
            $items[] = $this->getItem($product);
        }
        return $items;
    }

    private function getItem(Product $product): array
    {
        return [
            "sku" => $product->id,
            "name" => $product->title,
            "category" => "physical",
            "qty" => "1",
            "price" => $product->price,
            "tax" => 0,
        ];
    }

    private function getTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item)
            $total += $item['price'];
        return $total;
    }

    public function bodyRequest(string $locale): array
    {
        $body = [
            'locale' => $locale,
            'auth' => $this->bodyRequestInformation(),
            'buyer' => $this->getBuyer($this->order),
            'payment' => $this->getPayment($this->order),
            'expiration' => date('c', strtotime('+1 hour')),
            'returnUrl' => route('orders.index', ['order' => $this->order->id]),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->header('user-agent')];

        return array($body, $this->order->id);
    }
}
