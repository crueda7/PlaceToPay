<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

class ShoppingCartRepositoryImpl implements \App\Interfaces\ShoppingCartRepository
{

    public function cart(): Collection
    {
        // TODO: Implement cart() method.
    }

    public function saveItem(int $productId): Collection
    {
        // TODO: Implement saveItem() method.
    }

    public function removeItem(int $productId): Collection
    {
        // TODO: Implement removeItem() method.
    }
}
