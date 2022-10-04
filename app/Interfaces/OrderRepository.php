<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepository
{
    public function orders(): Collection;

    public function order(): Order;

    public function orderById(int $orderId): Order;

    public function save(array $newOrder): Order;
}
