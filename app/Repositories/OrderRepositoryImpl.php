<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepositoryImpl implements \App\Interfaces\OrderRepository
{

    public function orders(): Collection
    {
        // TODO: Implement orders() method.
    }

    public function order(): Order
    {
        // TODO: Implement order() method.
    }

    public function orderById(int $orderId): Order
    {
        // TODO: Implement orderById() method.
    }

    public function save(array $newOrder): Order
    {
        // TODO: Implement save() method.
    }
}
