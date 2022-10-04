<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ShoppingCartRepository
{
    public function cart(): Collection;

    public function saveItem(int $productId): Collection;

    public function removeItem(int $productId): Collection;
}
